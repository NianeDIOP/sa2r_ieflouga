<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CompteController extends Controller
{
    /**
     * Display a listing of accounts
     */
    public function index(Request $request)
    {
        $query = User::where('type', 'etablissement')
            ->with([
                'etablissement.rapports' => function($q) {
                    $q->where('annee_scolaire', '2024-2025')
                      ->with('infoDirecteur');
                }
            ]);
        
        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('etablissement', function($sq) use ($search) {
                      $sq->where('etablissement', 'like', "%{$search}%");
                  })
                  ->orWhereHas('etablissement.rapports.infoDirecteur', function($sq) use ($search) {
                      $sq->where('directeur_nom', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filtre par commune (via la relation etablissement)
        if ($request->filled('commune')) {
            $query->whereHas('etablissement', function($q) use ($request) {
                $q->where('commune', $request->commune);
            });
        }
        
        // Filtre par zone (via la relation etablissement)
        if ($request->filled('zone')) {
            $query->whereHas('etablissement', function($q) use ($request) {
                $q->where('zone', $request->zone);
            });
        }
        
        // Filtre par statut
        if ($request->filled('status')) {
            $isActive = $request->status === 'actif';
            $query->where('is_active', $isActive);
        }
        
        $comptes = $query->orderBy('code')->paginate(20)->withQueryString();
        
        $stats = [
            'total' => User::where('type', 'etablissement')->count(),
            'actifs' => User::where('type', 'etablissement')->where('is_active', true)->count(),
            'inactifs' => User::where('type', 'etablissement')->where('is_active', false)->count()
        ];
        
        // Charger les listes depuis la base de données (etablissements)
        $lists = [
            'communes' => Etablissement::whereNotNull('commune')
                ->distinct()
                ->orderBy('commune')
                ->pluck('commune')
                ->toArray(),
            'zones' => Etablissement::whereNotNull('zone')
                ->distinct()
                ->orderBy('zone')
                ->pluck('zone')
                ->toArray()
        ];
        
        return view('admin.comptes.index', compact('comptes', 'stats', 'lists'));
    }
    
    /**
     * Charger les listes depuis le fichier utile.xlsx
     */
    private function loadListsFromExcel()
    {
        try {
            $filePath = base_path('utile.xlsx');
            
            if (!file_exists($filePath)) {
                return $this->getDefaultLists();
            }
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            
            array_shift($data);
            
            $communes = [];
            $zones = [];
            
            foreach ($data as $row) {
                if (!empty($row[1])) $communes[] = trim($row[1]);
                if (!empty($row[3])) $zones[] = trim($row[3]);
            }
            
            $communes = array_unique($communes);
            $zones = array_unique($zones);
            
            sort($communes);
            sort($zones);
            
            return [
                'communes' => $communes,
                'zones' => $zones
            ];
        } catch (\Exception $e) {
            return $this->getDefaultLists();
        }
    }
    
    private function getDefaultLists()
    {
        return [
            'communes' => ['Gande', 'Guet Ardo', 'Kelle Gueye', 'Keur Momar Sarr', 'Koki', 'Leona', 'Louga', 'Mbediene', 'Ndiagne', 'Nguer Malal', 'Ngueune Sarr', 'Nguidile', 'Niomre', 'Pete Ouarack', 'Sakal', 'Syer', 'Thiamene Cayor'],
            'zones' => ['Artiellerie', 'Baity Gueye', 'Barale Gabar', 'Gande Gouye Mbeute', 'Guet Ardo', 'Kelle Gueye', 'Keur Coura Diery', 'Keur Momar Sarr 1', 'Keur Momar Sarr 2', 'Keur Serigne Louga', 'Koki', 'Leona', 'Mbediene', 'Ndiagne', 'Ndiamb Fall', 'Ndiang Khoule', 'Ngadji Sarr', 'Ngeune Sarr', 'Nguer Malal', 'Nguidile', 'Niomre', 'Ouarack', 'Pete Ouarack', 'Sakal', 'Santhiaba', 'Santhiou Baity', 'Syer Diery', 'Syer tack', 'Thiamene']
        ];
    }

    /**
     * Toggle account status
     */
    public function toggleStatus(User $compte)
    {
        $compte->is_active = !$compte->is_active;
        $compte->save();

        return response()->json([
            'success' => true,
            'is_active' => $compte->is_active,
            'message' => $compte->is_active ? 'Compte activé' : 'Compte désactivé'
        ]);
    }

    /**
     * Update director information
     */
    public function updateDirecteur(Request $request, User $compte)
    {
        $validated = $request->validate([
            'directeur_nom' => 'nullable|string|max:150',
            'directeur_telephone' => 'nullable|string|max:20'
        ]);

        $compte->update($validated);

        // Synchroniser avec tous les rapports existants de cet établissement
        if ($compte->etablissement) {
            \App\Models\RapportInfoDirecteur::whereHas('rapport', function($query) use ($compte) {
                $query->where('etablissement_id', $compte->etablissement->id);
            })->update([
                'directeur_nom' => $validated['directeur_nom'],
                'directeur_contact_1' => $validated['directeur_telephone']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Informations du directeur mises à jour avec succès (synchronisées avec les rapports)'
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request, User $compte)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6'
        ]);

        $compte->password = Hash::make($validated['password']);
        $compte->save();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }

    /**
     * Reset password to default
     */
    public function resetPassword(User $compte)
    {
        $compte->password = Hash::make('sa2r2025');
        $compte->save();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé à: sa2r2025'
        ]);
    }

    /**
     * Reset all passwords to default
     */
    public function resetAllPasswords()
    {
        $defaultPassword = Hash::make('sa2r2025');
        
        $count = User::where('type', 'etablissement')
            ->update(['password' => $defaultPassword]);

        return response()->json([
            'success' => true,
            'message' => "$count mot(s) de passe réinitialisé(s) à: sa2r2025"
        ]);
    }

    /**
     * Get account history
     */
    public function history(User $compte)
    {
        $compte->load('etablissement');
        
        return response()->json([
            'user' => [
                'code' => $compte->code,
                'etablissement' => $compte->etablissement,
                'login_count' => $compte->login_count,
                'last_login_at' => $compte->last_login_at ? $compte->last_login_at->format('d/m/Y H:i') : null,
                'directeur_nom' => $compte->directeur_nom,
                'directeur_telephone' => $compte->directeur_telephone,
                'created_at' => $compte->created_at->format('d/m/Y H:i'),
                'is_active' => $compte->is_active
            ]
        ]);
    }

    /**
     * Export accounts to Excel
     */
    public function export()
    {
        $comptes = User::where('type', 'etablissement')
            ->with('etablissement')
            ->orderBy('code')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // En-têtes
        $headers = [
            'Code (Login)',
            'Mot de passe',
            'Établissement',
            'Commune',
            'Zone',
            'Directeur Nom',
            'Directeur Téléphone',
            'Statut',
            'Connexions',
            'Dernière connexion'
        ];

        $sheet->fromArray($headers, null, 'A1');

        // Style de l'en-tête
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Données
        $row = 2;
        foreach ($comptes as $compte) {
            $sheet->fromArray([
                $compte->code,
                'sa2r2025',
                $compte->etablissement->etablissement ?? 'N/A',
                $compte->commune ?? '',
                $compte->zone ?? '',
                $compte->directeur_nom ?? '',
                $compte->directeur_telephone ?? '',
                $compte->is_active ? 'Actif' : 'Inactif',
                $compte->login_count,
                $compte->last_login_at ? $compte->last_login_at->format('d/m/Y H:i') : 'Jamais'
            ], null, 'A' . $row);
            $row++;
        }

        // Auto-size colonnes
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Génération du fichier
        $filename = 'comptes_etablissements_' . date('Y-m-d_His') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
