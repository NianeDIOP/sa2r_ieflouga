<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\AnneeScolaire;
use App\Services\RapportQualityService;
use Illuminate\Http\Request;

class ControleQualiteController extends Controller
{
    private RapportQualityService $qualityService;

    public function __construct(RapportQualityService $qualityService)
    {
        $this->qualityService = $qualityService;
    }

    /**
     * Vue principale avec 3 onglets
     */
    public function index(Request $request)
    {
        $anneeActive = AnneeScolaire::getActive();
        $anneeScolaire = $request->input('annee_scolaire', $anneeActive ? $anneeActive->annee : date('Y') . '-' . (date('Y') + 1));
        
        // Charger les rapports avec relations
        $rapports = Rapport::with([
            'etablissement',
            'effectifs',
            'personnelEnseignant',
            'infoDirecteur',
            'infrastructuresBase',
            'cfee',
            'cmg',
            'recrutementCi',
            'entreeSixieme',
            'manuelsEleves',
            'manuelsMaitre',
            'ressourcesFinancieres',
        ])
        ->where('annee_scolaire', $anneeScolaire)
        ->where('statut', 'validé')
        ->get();

        // Calculer les scores pour chaque rapport
        $rapportsAvecScores = $rapports->map(function ($rapport) {
            $qualite = $this->qualityService->calculerScoreGlobal($rapport);
            return [
                'rapport' => $rapport,
                'qualite' => $qualite,
            ];
        })->sortByDesc('qualite.score_total');

        // Préparer les données pour Alpine.js (éviter problème de syntaxe Blade)
        $rapportsData = $rapportsAvecScores->map(function($item) {
            return [
                'rapport_id' => $item['rapport']->id,
                'etablissement' => $item['rapport']->etablissement->etablissement ?? $item['rapport']->etablissement->nom ?? 'Établissement non défini',
                'zone' => $item['rapport']->etablissement->zone ?? 'Non définie',
                'score_total' => $item['qualite']['score_total'],
                'completude_score' => $item['qualite']['completude']['score'],
                'completude_pct' => $item['qualite']['completude']['pourcentage'],
                'coherence_score' => $item['qualite']['coherence']['score'],
                'coherence_pct' => $item['qualite']['coherence']['pourcentage'],
                'precision_score' => $item['qualite']['precision']['score'],
                'precision_pct' => $item['qualite']['precision']['pourcentage'],
                'badge_label' => $item['qualite']['badge']['label'],
                'badge_icon' => $item['qualite']['badge']['icon'],
                'badge_color' => $item['qualite']['badge']['color'],
                'anomalies' => $item['qualite']['anomalies'],
                'anomalies_count' => count($item['qualite']['anomalies']),
            ];
        })->values();

        // Statistiques globales
        $statistiques = $this->qualityService->statistiquesGlobales($anneeScolaire);

        // Années scolaires disponibles
        $anneesScolaires = Rapport::select('annee_scolaire')
            ->distinct()
            ->orderBy('annee_scolaire', 'desc')
            ->pluck('annee_scolaire');

        return view('admin.controle-qualite.index', compact(
            'rapportsAvecScores',
            'rapportsData',
            'statistiques',
            'anneeScolaire',
            'anneesScolaires'
        ));
    }

    /**
     * Détails d'un rapport spécifique
     */
    public function show(Rapport $rapport)
    {
        $rapport->load([
            'etablissement',
            'effectifs',
            'personnelEnseignant',
            'infoDirecteur',
            'infrastructuresBase',
            'cfee',
            'cmg',
            'recrutementCi',
            'entreeSixieme',
            'capitalImmobilier',
            'capitalMobilier',
            'materielDidactique',
            'equipementInformatique',
            'manuelsEleves',
            'manuelsMaitre',
            'dictionnaires',
            'ressourcesFinancieres',
            'structuresCommunautaires',
            'languesProjets',
        ]);

        $qualite = $this->qualityService->calculerScoreGlobal($rapport);
        $recommandations = $this->qualityService->genererRecommandations($rapport);

        return view('admin.controle-qualite.show', compact(
            'rapport',
            'qualite',
            'recommandations'
        ));
    }
}
