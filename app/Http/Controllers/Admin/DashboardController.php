<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\Rapport;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard admin avec carte interactive des zones
     */
    public function index()
    {
        // Récupérer l'année scolaire active
        $anneeScolaireActive = AnneeScolaire::where('is_active', true)->first();
        
        if (!$anneeScolaireActive) {
            return view('admin.dashboard')->with('error', 'Aucune année scolaire active. Veuillez activer une année.');
        }

        // Récupérer DYNAMIQUEMENT toutes les zones existantes en base
        $zonesData = Etablissement::select('zone')
            ->whereNotNull('zone')
            ->where('zone', '!=', '')
            ->distinct()
            ->orderBy('zone')
            ->get()
            ->map(function($item) use ($anneeScolaireActive) {
                return $this->calculateZoneStats($item->zone, $anneeScolaireActive->annee);
            })
            ->filter(); // Enlever les zones sans données

        // Pas besoin de positions car on utilise une grille CSS
        // Les zones seront affichées dans l'ordre alphabétique

        // Statistiques globales
        $stats = [
            'total_etablissements' => Etablissement::count(),
            'total_zones' => $zonesData->count(),
            'total_rapports_annee' => Rapport::where('annee_scolaire', $anneeScolaireActive->annee)->count(),
            'taux_soumission_global' => $this->calculateTauxSoumissionGlobal($anneeScolaireActive->annee)
        ];

        return view('admin.dashboard', [
            'zonesData' => $zonesData,
            'anneeScolaireActive' => $anneeScolaireActive,
            'stats' => $stats
        ]);
    }

    /**
     * Calculer les statistiques pour une zone spécifique
     */
    private function calculateZoneStats($zone, $anneeScolaire)
    {
        // Total établissements dans cette zone
        $totalEtablissements = Etablissement::where('zone', $zone)->count();
        
        if ($totalEtablissements === 0) {
            return null; // Zone vide, on l'ignore
        }

        // IDs des établissements de la zone
        $etablissementIds = Etablissement::where('zone', $zone)->pluck('id');

        // Compter les rapports par statut pour cette zone
        $rapportsStats = Rapport::whereIn('etablissement_id', $etablissementIds)
            ->where('annee_scolaire', $anneeScolaire)
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $totalRapports = $rapportsStats->sum();
        $rapportsSoumis = ($rapportsStats->get('soumis', 0) ?? 0);
        $rapportsValides = ($rapportsStats->get('validé', 0) ?? 0);
        $rapportsRejetes = ($rapportsStats->get('rejeté', 0) ?? 0);
        $rapportsBrouillons = ($rapportsStats->get('brouillon', 0) ?? 0);
        
        // Calculer le pourcentage (soumis + validés)
        $rapportsAvances = $rapportsSoumis + $rapportsValides;
        $pourcentage = $totalEtablissements > 0 
            ? round(($rapportsAvances / $totalEtablissements) * 100) 
            : 0;

        // Déterminer la couleur selon le pourcentage
        $couleur = $this->getCouleurByPourcentage($pourcentage);

        return [
            'nom' => $zone,
            'total_etablissements' => $totalEtablissements,
            'total_rapports' => $totalRapports,
            'rapports_soumis' => $rapportsSoumis,
            'rapports_valides' => $rapportsValides,
            'rapports_rejetes' => $rapportsRejetes,
            'rapports_brouillons' => $rapportsBrouillons,
            'sans_rapport' => $totalEtablissements - $totalRapports,
            'pourcentage' => $pourcentage,
            'couleur' => $couleur
        ];
    }

    /**
     * Déterminer la couleur selon le pourcentage de soumission
     */
    private function getCouleurByPourcentage($pourcentage)
    {
        if ($pourcentage >= 80) {
            return [
                'bg' => 'bg-emerald-500',
                'from' => 'from-emerald-500',
                'to' => 'to-emerald-600',
                'border' => 'border-emerald-400',
                'text' => 'text-emerald-600',
                'hover' => 'hover:bg-emerald-600',
                'name' => 'emerald'
            ];
        } elseif ($pourcentage >= 50) {
            return [
                'bg' => 'bg-amber-500',
                'from' => 'from-amber-500',
                'to' => 'to-amber-600',
                'border' => 'border-amber-400',
                'text' => 'text-amber-600',
                'hover' => 'hover:bg-amber-600',
                'name' => 'amber'
            ];
        } else {
            return [
                'bg' => 'bg-red-500',
                'from' => 'from-red-500',
                'to' => 'to-red-600',
                'border' => 'border-red-400',
                'text' => 'text-red-600',
                'hover' => 'hover:bg-red-600',
                'name' => 'red'
            ];
        }
    }

    /**
     * Calculer le taux de soumission global
     */
    private function calculateTauxSoumissionGlobal($anneeScolaire)
    {
        $totalEtablissements = Etablissement::count();
        
        if ($totalEtablissements === 0) {
            return 0;
        }

        $rapportsAvances = Rapport::where('annee_scolaire', $anneeScolaire)
            ->whereIn('statut', ['soumis', 'validé'])
            ->count();

        return round(($rapportsAvances / $totalEtablissements) * 100);
    }

    /**
     * API pour récupérer les établissements d'une zone (pour la modale)
     */
    public function getEtablissementsZone(Request $request)
    {
        $zone = $request->input('zone');
        $anneeScolaire = $request->input('annee_scolaire');

        $etablissements = Etablissement::where('zone', $zone)
            ->with(['rapports' => function($q) use ($anneeScolaire) {
                $q->where('annee_scolaire', $anneeScolaire);
            }])
            ->orderBy('etablissement')
            ->get()
            ->map(function($etab) {
                $rapport = $etab->rapports->first();
                return [
                    'id' => $etab->id,
                    'nom' => $etab->etablissement,
                    'code' => $etab->code,
                    'commune' => $etab->commune,
                    'arrondissement' => $etab->arrondissement,
                    'statut' => $etab->statut,
                    'rapport' => $rapport ? [
                        'id' => $rapport->id,
                        'statut' => $rapport->statut,
                        'date_soumission' => $rapport->date_soumission?->format('d/m/Y'),
                        'date_validation' => $rapport->date_validation?->format('d/m/Y')
                    ] : null
                ];
            });

        return response()->json([
            'zone' => $zone,
            'etablissements' => $etablissements
        ]);
    }
}
