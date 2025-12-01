<?php

namespace App\Http\Controllers\Etablissement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard établissement
     */
    public function index(Request $request)
    {
        $user = Auth::guard('etablissement')->user();
        
        // Récupérer les informations complètes de l'établissement
        $etablissement = Etablissement::where('code', $user->code)->first();
        
        if (!$etablissement) {
            return redirect()->route('login')->with('error', 'Établissement non trouvé');
        }
        
        // Récupérer toutes les années scolaires disponibles
        $anneesDisponibles = AnneeScolaire::orderBy('annee', 'desc')->get();
        
        // Récupérer l'année scolaire active
        $anneeScolaireActive = AnneeScolaire::getActive();
        
        // Récupérer l'année sélectionnée (ou année active par défaut)
        $anneeSelectionnee = $request->get('annee', $anneeScolaireActive?->annee);
        
        // Récupérer le rapport avec TOUTES les relations
        $rapport = \App\Models\Rapport::where('etablissement_id', $etablissement->id)
            ->where('annee_scolaire', $anneeSelectionnee)
            ->with([
                'effectifs', 
                'personnelEnseignant',
                'infoDirecteur',
                'infrastructuresBase',
                'ressourcesFinancieres',
                'structuresCommunautaires',
                'cfee',
                'entreeSixieme',
                'cmg',
                'recrutementCi',
                'manuelsEleves',
                'manuelsMaitre',
                'dictionnaires',
                'materielDidactique',
                'capitalImmobilier',
                'capitalMobilier',
                'equipementInformatique'
            ])
            ->first();
        
        // Calculer les statistiques détaillées
        $stats = $this->calculerStatistiques($rapport);

        return view('etablissement.dashboard', compact(
            'etablissement', 
            'user', 
            'anneeScolaireActive',
            'anneesDisponibles',
            'anneeSelectionnee',
            'rapport',
            'stats'
        ));
    }
    
    /**
     * Calculer toutes les statistiques pour le dashboard
     */
    private function calculerStatistiques($rapport)
    {
        if (!$rapport) {
            return [
                'statut_rapport' => 'aucun',
                'total_eleves' => 0,
                'total_garcons' => 0,
                'total_filles' => 0,
                'taux_feminisation' => 0,
                'total_classes' => 0,
                'total_enseignants' => 0,
                'ratio_eleves_enseignant' => 0,
                'taux_reussite_cfee' => 0,
                'taux_admission_6eme' => 0,
                'infrastructures_bon_etat' => 0,
                'salles_dur' => 0,
                'toilettes_total' => 0,
                'ordinateurs_total' => 0,
                'manuels_total' => 0,
                'dictionnaires_total' => 0,
                'cge_membres' => 0,
                'budget_total' => 0,
            ];
        }
        
        // Effectifs
        $totalEleves = $rapport->effectifs->sum('effectif_total');
        $totalGarcons = $rapport->effectifs->sum('garcons');
        $totalFilles = $rapport->effectifs->sum('filles');
        $totalClasses = $rapport->effectifs->sum('nombre_classes');
        $tauxFeminisation = $totalEleves > 0 ? ($totalFilles / $totalEleves) * 100 : 0;
        
        // Personnel
        $totalEnseignants = $rapport->personnelEnseignant?->total_personnel ?? 0;
        $ratioElevesEnseignant = $rapport->personnelEnseignant?->ratio_eleves_enseignant ?? 0;
        
        // Examens
        $tauxReussiteCfee = 0;
        if ($rapport->cfee && ($rapport->cfee->candidats_total ?? 0) > 0) {
            $tauxReussiteCfee = ($rapport->cfee->admis_total / $rapport->cfee->candidats_total) * 100;
        }
        
        $tauxAdmission6eme = 0;
        if ($rapport->entreeSixieme && ($rapport->entreeSixieme->candidats_total ?? 0) > 0) {
            $tauxAdmission6eme = ($rapport->entreeSixieme->admis_total / $rapport->entreeSixieme->candidats_total) * 100;
        }
        
        // Infrastructures
        $sallesDur = $rapport->capitalImmobilier?->salles_dur_total ?? 0;
        $sallesDurBonEtat = $rapport->capitalImmobilier?->salles_dur_bon_etat ?? 0;
        $infrastructuresBonEtat = $sallesDur > 0 ? ($sallesDurBonEtat / $sallesDur) * 100 : 0;
        
        $toilettesTotal = ($rapport->capitalImmobilier?->toilettes_garcons_total ?? 0) +
                         ($rapport->capitalImmobilier?->toilettes_filles_total ?? 0) +
                         ($rapport->capitalImmobilier?->toilettes_enseignants_total ?? 0);
        
        // Équipements
        $ordinateursTotal = ($rapport->equipementInformatique?->ordinateurs_fixes_total ?? 0) +
                           ($rapport->equipementInformatique?->ordinateurs_portables_total ?? 0);
        
        // Manuels
        $manuelsTotal = $rapport->manuelsEleves->sum(function($m) {
            return ($m->lc_francais ?? 0) + ($m->mathematiques ?? 0) + ($m->edd ?? 0);
        });
        
        $dictionnairesTotal = ($rapport->dictionnaires?->dico_francais_total ?? 0) +
                             ($rapport->dictionnaires?->dico_arabe_total ?? 0);
        
        // Structures communautaires
        $cgeMembres = $rapport->structuresCommunautaires?->cge_total ?? 0;
        
        // Budget
        $budgetTotal = ($rapport->ressourcesFinancieres?->etat ?? 0) +
                      ($rapport->ressourcesFinancieres?->partenaires ?? 0) +
                      ($rapport->ressourcesFinancieres?->collectivites_locales ?? 0);
        
        return [
            'statut_rapport' => $rapport->statut ?? 'aucun',
            'total_eleves' => $totalEleves,
            'total_garcons' => $totalGarcons,
            'total_filles' => $totalFilles,
            'taux_feminisation' => $tauxFeminisation,
            'total_classes' => $totalClasses,
            'total_enseignants' => $totalEnseignants,
            'ratio_eleves_enseignant' => $ratioElevesEnseignant,
            'taux_reussite_cfee' => $tauxReussiteCfee,
            'taux_admission_6eme' => $tauxAdmission6eme,
            'infrastructures_bon_etat' => $infrastructuresBonEtat,
            'salles_dur' => $sallesDur,
            'toilettes_total' => $toilettesTotal,
            'ordinateurs_total' => $ordinateursTotal,
            'manuels_total' => $manuelsTotal,
            'dictionnaires_total' => $dictionnairesTotal,
            'cge_membres' => $cgeMembres,
            'budget_total' => $budgetTotal,
        ];
    }
}
