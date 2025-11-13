<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EtablissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Etablissement::with('user');
        
        // Filtre de recherche (nom ou code)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('etablissement', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        // Filtre par commune
        if ($request->filled('commune')) {
            $query->where('commune', $request->commune);
        }
        
        // Filtre par zone
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $etablissements = $query->orderBy('etablissement')->paginate(20)->withQueryString();
        
        $stats = [
            'total' => Etablissement::count(),
            'public' => Etablissement::where('statut', 'Public')->count(),
            'prive' => Etablissement::where('statut', 'Privé')->count()
        ];
        
        // Charger les listes depuis utile.xlsx
        $lists = $this->loadListsFromExcel();
        
        return view('admin.etablissements.index', compact('etablissements', 'stats', 'lists'));
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
            
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            
            // Ignorer la première ligne (en-têtes)
            array_shift($data);
            
            $arrondissements = [];
            $communes = [];
            $districts = [];
            $zones = [];
            
            foreach ($data as $row) {
                if (!empty($row[0])) $arrondissements[] = trim($row[0]);
                if (!empty($row[1])) $communes[] = trim($row[1]);
                if (!empty($row[2])) $districts[] = trim($row[2]);
                if (!empty($row[3])) $zones[] = trim($row[3]);
            }
            
            // Obtenir les valeurs uniques et trier
            $arrondissements = array_unique($arrondissements);
            $communes = array_unique($communes);
            $districts = array_unique($districts);
            $zones = array_unique($zones);
            
            sort($arrondissements);
            sort($communes);
            sort($districts);
            sort($zones);
            
            return [
                'arrondissements' => $arrondissements,
                'communes' => $communes,
                'districts' => $districts,
                'zones' => $zones
            ];
        } catch (\Exception $e) {
            return $this->getDefaultLists();
        }
    }
    
    /**
     * Obtenir les listes par défaut si le fichier n'existe pas
     */
    private function getDefaultLists()
    {
        return [
            'arrondissements' => ['Chaine Urbaine', 'Keur Momar Sarr', 'Mbediene', 'Sakal', 'koki'],
            'communes' => ['Gande', 'Guet Ardo', 'Kelle Gueye', 'Keur Momar Sarr', 'Koki', 'Leona', 'Louga', 'Mbediene', 'Ndiagne', 'Nguer Malal', 'Ngueune Sarr', 'Nguidile', 'Niomre', 'Pete Ouarack', 'Sakal', 'Syer', 'Thiamene Cayor'],
            'districts' => ['Keur Momar Sarr', 'Koki', 'Leona', 'Mbediene', 'Ndiagne', 'Sakal'],
            'zones' => ['Artiellerie', 'Baity Gueye', 'Barale Gabar', 'Gande Gouye Mbeute', 'Guet Ardo', 'Kelle Gueye', 'Keur Coura Diery', 'Keur Momar Sarr 1', 'Keur Momar Sarr 2', 'Keur Serigne Louga', 'Koki', 'Leona', 'Mbediene', 'Ndiagne', 'Ndiamb Fall', 'Ndiang Khoule', 'Ngadji Sarr', 'Ngeune Sarr', 'Nguer Malal', 'Nguidile', 'Niomre', 'Ouarack', 'Pete Ouarack', 'Sakal', 'Santhiaba', 'Santhiou Baity', 'Syer Diery', 'Syer tack', 'Thiamene']
        ];
    }

    /**
     * Show the specified resource.
     */
    public function show(Etablissement $etablissement)
    {
        $user = $etablissement->user;
        return response()->json([
            'etablissement' => $etablissement,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'etablissement' => 'required|string|max:150',
            'code' => 'required|string|size:10|unique:etablissements,code',
            'arrondissement' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'zone' => 'nullable|string|max:100',
            'geo_ref_x' => 'nullable|integer',
            'geo_ref_y' => 'nullable|integer',
            'statut' => 'nullable|string|max:50',
            'type_statut' => 'nullable|string|max:100',
            'date_creation' => 'nullable|integer|min:1900|max:2100',
            'date_ouverture' => 'nullable|integer|min:1900|max:2100'
        ]);

        $etablissement = Etablissement::create($validated);
        $etablissement->createUserAccount();

        return redirect()->route('admin.etablissements.index')
            ->with('success', 'Établissement créé avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etablissement $etablissement)
    {
        $validated = $request->validate([
            'etablissement' => 'required|string|max:150',
            'code' => 'required|string|size:10|unique:etablissements,code,' . $etablissement->id,
            'arrondissement' => 'nullable|string|max:100',
            'commune' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'zone' => 'nullable|string|max:100',
            'geo_ref_x' => 'nullable|integer',
            'geo_ref_y' => 'nullable|integer',
            'statut' => 'nullable|string|max:50',
            'type_statut' => 'nullable|string|max:100',
            'date_creation' => 'nullable|integer|min:1900|max:2100',
            'date_ouverture' => 'nullable|integer|min:1900|max:2100'
        ]);

        $etablissement->update($validated);

        return redirect()->route('admin.etablissements.index')
            ->with('success', 'Établissement mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etablissement $etablissement)
    {
        // Supprimer aussi le compte utilisateur associé
        if ($etablissement->user) {
            $etablissement->user->delete();
        }
        
        $etablissement->delete();

        return redirect()->route('admin.etablissements.index')
            ->with('success', 'Établissement supprimé avec succès');
    }

    /**
     * Import data from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        // Augmenter le temps d'exécution pour les gros imports
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Ignorer la première ligne (en-têtes)
            array_shift($rows);

            $imported = 0;
            $errors = [];
            
            // Préparer le mot de passe une seule fois pour tous les comptes
            $defaultPassword = bcrypt('sa2r2025');

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                // Ignorer les lignes vides
                if (empty(array_filter($row))) continue;

                try {
                    $code = $row[5] ?? null;
                    
                    // Vérifier si le code existe déjà
                    if ($code && Etablissement::where('code', $code)->exists()) {
                        $errors[] = "Ligne " . ($index + 2) . ": Code '$code' existe déjà";
                        continue;
                    }
                    
                    // Valider les données minimales
                    if (empty($row[0]) || empty($code)) {
                        $errors[] = "Ligne " . ($index + 2) . ": Établissement et code sont obligatoires";
                        continue;
                    }
                    
                    // Valider la longueur du code
                    if (strlen($code) !== 10) {
                        $errors[] = "Ligne " . ($index + 2) . ": Le code doit contenir exactement 10 caractères";
                        continue;
                    }

                    $etablissement = Etablissement::create([
                        'etablissement' => $row[0],
                        'arrondissement' => $row[1] ?? null,
                        'commune' => $row[2] ?? null,
                        'district' => $row[3] ?? null,
                        'zone' => $row[4] ?? null,
                        'code' => $code,
                        'geo_ref_x' => !empty($row[6]) ? (int)$row[6] : null,
                        'geo_ref_y' => !empty($row[7]) ? (int)$row[7] : null,
                        'statut' => $row[8] ?? null,
                        'type_statut' => $row[9] ?? null,
                        'date_creation' => !empty($row[10]) ? (int)$row[10] : null,
                        'date_ouverture' => !empty($row[11]) ? (int)$row[11] : null,
                    ]);

                    // Créer le compte utilisateur avec le mot de passe pré-hashé
                    \App\Models\User::create([
                        'code' => $code,
                        'password' => $defaultPassword,
                        'type' => 'etablissement',
                        'is_active' => true,
                        'etablissement_id' => $etablissement->id
                    ]);

                    $imported++;
                    
                    // Commit tous les 50 enregistrements pour libérer la mémoire
                    if ($imported % 50 === 0) {
                        DB::commit();
                        DB::beginTransaction();
                    }
                    
                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "$imported établissement(s) importé(s) avec succès";
            if (!empty($errors)) {
                $message .= ". " . count($errors) . " erreur(s) détectée(s)";
            }

            return redirect()->route('admin.etablissements.index')
                ->with('success', $message)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.etablissements.index')
                ->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        $headers = [
            'etablissement',
            'arrondissement',
            'commune',
            'district',
            'zone',
            'code',
            'geo_ref_x',
            'geo_ref_y',
            'statut',
            'type_statut',
            'date_creation',
            'date_ouverture'
        ];

        $filename = 'template_etablissements.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');

        // Générer le fichier Excel en mémoire
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return response($excelOutput, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Toggle user account status (active/inactive)
     */
    public function toggleStatus(Etablissement $etablissement)
    {
        $user = $etablissement->user;
        
        if (!$user) {
            return response()->json(['error' => 'Aucun compte utilisateur associé'], 404);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active,
            'message' => $user->is_active ? 'Compte activé' : 'Compte désactivé'
        ]);
    }

    /**
     * Reset user password
     */
    public function resetPassword(Etablissement $etablissement)
    {
        $user = $etablissement->user;
        
        if (!$user) {
            return response()->json(['error' => 'Aucun compte utilisateur associé'], 404);
        }

        $user->password = bcrypt('sa2r2025');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé à: sa2r2025'
        ]);
    }
}
