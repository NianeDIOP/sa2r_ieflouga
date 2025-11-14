<?php

namespace App\Http\Controllers\Etablissement;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\RapportInfoDirecteur;
use App\Models\RapportInfrastructuresBase;
use App\Models\RapportStructuresCommunautaires;
use App\Models\RapportLanguesProjets;
use App\Models\RapportRessourcesFinancieres;
use App\Models\RapportCmg;
use App\Models\RapportCfee;
use App\Models\RapportEntreeSixieme;
use App\Models\RapportPersonnelEnseignant;
use App\Models\RapportMaterielDidactique;
use App\Models\RapportCapitalImmobilier;
use App\Models\RapportCapitalMobilier;
use App\Models\RapportEquipementInformatique;
use App\Models\RapportManuelsEleves;
use App\Models\RapportManuelsMaitre;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapportRentreeController extends Controller
{
    /**
     * Afficher le formulaire principal du rapport
     */
    public function index()
    {
        $etablissement = Auth::user()->etablissement;
        
        // Récupérer l'année scolaire active
        $anneeScolaireActive = AnneeScolaire::getActive();
        
        if (!$anneeScolaireActive) {
            return back()->with('error', 'Aucune année scolaire active. Contactez l\'administrateur.');
        }
        
        $anneeScolaire = $anneeScolaireActive->annee;
        
        // Récupérer ou créer le rapport unique pour cette année
        $rapport = Rapport::firstOrCreate(
            [
                'etablissement_id' => $etablissement->id,
                'annee_scolaire' => $anneeScolaire
            ],
            [
                'date_rapport' => now(),
                'statut' => 'brouillon'
            ]
        );

        // Charger toutes les relations
        $rapport->load([
            'infoDirecteur',
            'infrastructuresBase',
            'structuresCommunautaires',
            'languesProjets',
            'ressourcesFinancieres',
            'effectifs',
            'cmg',
            'recrutementCi',
            'cfee',
            'entreeSixieme',
            'personnelEnseignant',
            'materielDidactique',
            'capitalImmobilier',
            'capitalMobilier',
            'equipementInformatique',
            'manuelsEleves',
            'manuelsMaitre',
            'dictionnaires'
        ]);

        // Pré-remplir les infos directeur depuis la table users si elles n'existent pas dans le rapport
        $user = Auth::user();
        if (!$rapport->infoDirecteur && $user && ($user->directeur_nom || $user->directeur_telephone)) {
            $rapport->infoDirecteur()->create([
                'rapport_id' => $rapport->id,
                'directeur_nom' => $user->directeur_nom,
                'directeur_contact_1' => $user->directeur_telephone,
            ]);
            $rapport->load('infoDirecteur'); // Recharger la relation
        }

        return view('etablissement.rapport-rentree.index', compact('rapport', 'etablissement', 'anneeScolaireActive'));
    }

    /**
     * Sauvegarder ÉTAPE 1 - Info Directeur
     */
    public function saveInfoDirecteur(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'directeur_nom' => 'nullable|string|max:255',
            'directeur_contact_1' => 'nullable|string|max:255',
            'directeur_contact_2' => 'nullable|string|max:255',
            'directeur_email' => 'nullable|email|max:255',
            'distance_siege' => 'nullable|numeric|min:0',
        ]);

        // Sauvegarder dans la table rapport_info_directeur
        $rapport->infoDirecteur()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        // Synchroniser avec la table users (pour la gestion admin des comptes)
        $user = Auth::user();
        if ($user && $user->type === 'etablissement') {
            $user->update([
                'directeur_nom' => $validated['directeur_nom'] ?? $user->directeur_nom,
                'directeur_telephone' => $validated['directeur_contact_1'] ?? $user->directeur_telephone,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Informations directeur sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 1 - Infrastructures Base
     */
    public function saveInfrastructures(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'cpe_existe' => 'boolean',
            'cpe_nombre' => 'nullable|integer|min:0',
            'cloture_existe' => 'boolean',
            'cloture_type' => 'nullable|in:dur,provisoire,haie,autre',
            'eau_existe' => 'boolean',
            'eau_type' => 'nullable|in:robinet,forage,puits,autre',
            'electricite_existe' => 'boolean',
            'electricite_type' => 'nullable|in:SENELEC,solaire,groupe,autre',
            'connexion_internet_existe' => 'boolean',
            'connexion_internet_type' => 'nullable|in:fibre,4G,satellite,autre',
            'cantine_existe' => 'boolean',
            'cantine_type' => 'nullable|in:state,partenaire,communaute,autre',
        ]);

        // Gérer les checkboxes non cochées (false par défaut)
        $data = array_merge([
            'cpe_existe' => false,
            'cloture_existe' => false,
            'eau_existe' => false,
            'electricite_existe' => false,
            'connexion_internet_existe' => false,
            'cantine_existe' => false,
        ], $validated);

        // Nettoyer les champs dépendants si checkbox non cochée
        if (!$data['cpe_existe']) {
            $data['cpe_nombre'] = null;
        }
        if (!$data['cloture_existe']) {
            $data['cloture_type'] = null;
        }
        if (!$data['eau_existe']) {
            $data['eau_type'] = null;
        }
        if (!$data['electricite_existe']) {
            $data['electricite_type'] = null;
        }
        if (!$data['connexion_internet_existe']) {
            $data['connexion_internet_type'] = null;
        }
        if (!$data['cantine_existe']) {
            $data['cantine_type'] = null;
        }

        $rapport->infrastructuresBase()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Infrastructures sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 1 - Structures Communautaires
     */
    public function saveStructuresCommunautaires(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'cge_existe' => 'boolean',
            'cge_hommes' => 'nullable|integer|min:0',
            'cge_femmes' => 'nullable|integer|min:0',
            'cge_president_nom' => 'nullable|string',
            'cge_president_contact' => 'nullable|string',
            'cge_tresorier_nom' => 'nullable|string',
            'cge_tresorier_contact' => 'nullable|string',
            'gscol_existe' => 'boolean',
            'gscol_garcons' => 'nullable|integer|min:0',
            'gscol_filles' => 'nullable|integer|min:0',
            'gscol_president_nom' => 'nullable|string',
            'gscol_president_contact' => 'nullable|string',
            'ape_existe' => 'boolean',
            'ape_hommes' => 'nullable|integer|min:0',
            'ape_femmes' => 'nullable|integer|min:0',
            'ape_president_nom' => 'nullable|string',
            'ape_president_contact' => 'nullable|string',
            'ame_existe' => 'boolean',
            'ame_nombre' => 'nullable|integer|min:0',
            'ame_president_nom' => 'nullable|string',
            'ame_president_contact' => 'nullable|string',
        ]);

        // Gérer les checkboxes non cochées
        $data = array_merge([
            'cge_existe' => false,
            'gscol_existe' => false,
            'ape_existe' => false,
            'ame_existe' => false,
        ], $validated);

        // Nettoyer les champs dépendants
        if (!$data['cge_existe']) {
            $data['cge_hommes'] = null;
            $data['cge_femmes'] = null;
            $data['cge_president_nom'] = null;
            $data['cge_president_contact'] = null;
            $data['cge_tresorier_nom'] = null;
            $data['cge_tresorier_contact'] = null;
        }
        if (!$data['gscol_existe']) {
            $data['gscol_garcons'] = null;
            $data['gscol_filles'] = null;
            $data['gscol_president_nom'] = null;
            $data['gscol_president_contact'] = null;
        }
        if (!$data['ape_existe']) {
            $data['ape_hommes'] = null;
            $data['ape_femmes'] = null;
            $data['ape_president_nom'] = null;
            $data['ape_president_contact'] = null;
        }
        if (!$data['ame_existe']) {
            $data['ame_nombre'] = null;
            $data['ame_president_nom'] = null;
            $data['ame_president_contact'] = null;
        }

        $rapport->structuresCommunautaires()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Structures communautaires sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 1 - Langues & Projets
     */
    public function saveLanguesProjets(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'langue_nationale' => 'nullable|in:wolof,pulaar,serer,mandinka,soninke,autre',
            'enseignement_arabe_existe' => 'boolean',
            'projets_informatiques_existe' => 'boolean',
            'projets_informatiques_nom' => 'nullable|string',
        ]);

        // Gérer les checkboxes non cochées
        $data = array_merge([
            'enseignement_arabe_existe' => false,
            'projets_informatiques_existe' => false,
        ], $validated);

        // Nettoyer champs dépendants
        if (!$data['projets_informatiques_existe']) {
            $data['projets_informatiques_nom'] = null;
        }

        $rapport->languesProjets()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Langues & projets sauvegardés']);
    }

    /**
     * Sauvegarder ÉTAPE 1 - Ressources Financières
     */
    public function saveRessourcesFinancieres(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'subvention_etat_existe' => 'boolean',
            'subvention_etat_montant' => 'nullable|numeric|min:0',
            'subvention_partenaires_existe' => 'boolean',
            'subvention_partenaires_montant' => 'nullable|numeric|min:0',
            'subvention_collectivites_existe' => 'boolean',
            'subvention_collectivites_montant' => 'nullable|numeric|min:0',
            'subvention_communaute_existe' => 'boolean',
            'subvention_communaute_montant' => 'nullable|numeric|min:0',
            'ressources_generees_existe' => 'boolean',
            'ressources_generees_montant' => 'nullable|numeric|min:0',
        ]);

        // Gérer les checkboxes non cochées
        $data = array_merge([
            'subvention_etat_existe' => false,
            'subvention_partenaires_existe' => false,
            'subvention_collectivites_existe' => false,
            'subvention_communaute_existe' => false,
            'ressources_generees_existe' => false,
        ], $validated);

        // Nettoyer champs dépendants
        if (!$data['subvention_etat_existe']) {
            $data['subvention_etat_montant'] = null;
        }
        if (!$data['subvention_partenaires_existe']) {
            $data['subvention_partenaires_montant'] = null;
        }
        if (!$data['subvention_collectivites_existe']) {
            $data['subvention_collectivites_montant'] = null;
        }
        if (!$data['subvention_communaute_existe']) {
            $data['subvention_communaute_montant'] = null;
        }
        if (!$data['ressources_generees_existe']) {
            $data['ressources_generees_montant'] = null;
        }

        $rapport->ressourcesFinancieres()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Ressources financières sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 2 - Effectifs par classe
     */
    public function saveEffectifs(Request $request, Rapport $rapport)
    {
        // Validation pour les 6 niveaux
        $validated = $request->validate([
            'effectifs' => 'required|array',
            'effectifs.*.nombre_classes' => 'nullable|integer|min:0',
            'effectifs.*.effectif_garcons' => 'nullable|integer|min:0',
            'effectifs.*.effectif_filles' => 'nullable|integer|min:0',
            'effectifs.*.redoublants_garcons' => 'nullable|integer|min:0',
            'effectifs.*.redoublants_filles' => 'nullable|integer|min:0',
            'effectifs.*.abandons_garcons' => 'nullable|integer|min:0',
            'effectifs.*.abandons_filles' => 'nullable|integer|min:0',
            'effectifs.*.handicap_moteur_garcons' => 'nullable|integer|min:0',
            'effectifs.*.handicap_moteur_filles' => 'nullable|integer|min:0',
            'effectifs.*.handicap_visuel_garcons' => 'nullable|integer|min:0',
            'effectifs.*.handicap_visuel_filles' => 'nullable|integer|min:0',
            'effectifs.*.handicap_sourd_muet_garcons' => 'nullable|integer|min:0',
            'effectifs.*.handicap_sourd_muet_filles' => 'nullable|integer|min:0',
            'effectifs.*.handicap_deficience_intel_garcons' => 'nullable|integer|min:0',
            'effectifs.*.handicap_deficience_intel_filles' => 'nullable|integer|min:0',
            'effectifs.*.orphelins_garcons' => 'nullable|integer|min:0',
            'effectifs.*.orphelins_filles' => 'nullable|integer|min:0',
            'effectifs.*.sans_extrait_garcons' => 'nullable|integer|min:0',
            'effectifs.*.sans_extrait_filles' => 'nullable|integer|min:0',
        ]);

        // Sauvegarder chaque niveau
        foreach ($validated['effectifs'] as $niveau => $data) {
            $rapport->effectifs()->updateOrCreate(
                [
                    'rapport_id' => $rapport->id,
                    'niveau' => $niveau
                ],
                $data
            );
        }

        return response()->json(['success' => true, 'message' => 'Effectifs sauvegardés']);
    }

    /**
     * Sauvegarder ÉTAPE 3 - Recrutement CI
     */
    public function saveRecrutementCi(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'ci_nombre' => 'nullable|integer|min:0',
            'ci_combinaison_1' => 'nullable|string|max:255',
            'ci_combinaison_2' => 'nullable|string|max:255',
            'ci_combinaison_3' => 'nullable|string|max:255',
            'ci_combinaison_autres' => 'nullable|string|max:500',
            'ci_octobre_garcons' => 'nullable|integer|min:0',
            'ci_octobre_filles' => 'nullable|integer|min:0',
            'ci_janvier_garcons' => 'nullable|integer|min:0',
            'ci_janvier_filles' => 'nullable|integer|min:0',
            'ci_mai_garcons' => 'nullable|integer|min:0',
            'ci_mai_filles' => 'nullable|integer|min:0',
            'ci_statut' => 'nullable|in:homologue,non_homologue',
        ]);

        // Les totaux seront calculés automatiquement par le modèle
        $rapport->recrutementCi()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Données Recrutement CI sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 3 - CMG
     */
    public function saveCmg(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'cmg_nombre' => 'nullable|integer|min:0',
            'cmg_combinaison_1' => 'nullable|string|max:255',
            'cmg_combinaison_2' => 'nullable|string|max:255',
            'cmg_combinaison_3' => 'nullable|string|max:255',
            'cmg_combinaison_autres' => 'nullable|string|max:500',
        ]);

        $rapport->cmg()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Données CMG sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 3 - CFEE
     */
    public function saveCfee(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'cfee_candidats_total' => 'nullable|integer|min:0',
            'cfee_candidats_filles' => 'nullable|integer|min:0',
            'cfee_admis_total' => 'nullable|integer|min:0',
            'cfee_admis_filles' => 'nullable|integer|min:0',
        ]);

        // Vérification cohérence des données
        if (!empty($validated['cfee_candidats_filles']) && !empty($validated['cfee_candidats_total'])) {
            if ($validated['cfee_candidats_filles'] > $validated['cfee_candidats_total']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre de candidates ne peut pas être supérieur au nombre total de candidats'
                ], 422);
            }
        }

        if (!empty($validated['cfee_admis_filles']) && !empty($validated['cfee_admis_total'])) {
            if ($validated['cfee_admis_filles'] > $validated['cfee_admis_total']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre d\'admises ne peut pas être supérieur au nombre total d\'admis'
                ], 422);
            }
        }

        if (!empty($validated['cfee_admis_total']) && !empty($validated['cfee_candidats_total'])) {
            if ($validated['cfee_admis_total'] > $validated['cfee_candidats_total']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre d\'admis ne peut pas être supérieur au nombre de candidats'
                ], 422);
            }
        }

        if (!empty($validated['cfee_admis_filles']) && !empty($validated['cfee_candidats_filles'])) {
            if ($validated['cfee_admis_filles'] > $validated['cfee_candidats_filles']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre d\'admises ne peut pas être supérieur au nombre de candidates'
                ], 422);
            }
        }

        // Les taux de réussite seront calculés automatiquement par le modèle
        $rapport->cfee()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Données CFEE sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 3 - Entrée Sixième
     */
    public function saveEntreeSixieme(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'sixieme_candidats_total' => 'nullable|integer|min:0',
            'sixieme_candidats_filles' => 'nullable|integer|min:0',
            'sixieme_admis_total' => 'nullable|integer|min:0',
            'sixieme_admis_filles' => 'nullable|integer|min:0',
        ]);

        // Vérification cohérence des données
        if (!empty($validated['sixieme_candidats_filles']) && !empty($validated['sixieme_candidats_total'])) {
            if ($validated['sixieme_candidats_filles'] > $validated['sixieme_candidats_total']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre de candidates ne peut pas être supérieur au nombre total de candidats'
                ], 422);
            }
        }

        if (!empty($validated['sixieme_admis_filles']) && !empty($validated['sixieme_admis_total'])) {
            if ($validated['sixieme_admis_filles'] > $validated['sixieme_admis_total']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre d\'admises ne peut pas être supérieur au nombre total d\'admis'
                ], 422);
            }
        }

        if (!empty($validated['sixieme_admis_total']) && !empty($validated['sixieme_candidats_total'])) {
            if ($validated['sixieme_admis_total'] > $validated['sixieme_candidats_total']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre d\'admis ne peut pas être supérieur au nombre de candidats'
                ], 422);
            }
        }

        if (!empty($validated['sixieme_admis_filles']) && !empty($validated['sixieme_candidats_filles'])) {
            if ($validated['sixieme_admis_filles'] > $validated['sixieme_candidats_filles']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le nombre d\'admises ne peut pas être supérieur au nombre de candidates'
                ], 422);
            }
        }

        // Les taux d'admission seront calculés automatiquement par le modèle
        $rapport->entreeSixieme()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Données Entrée Sixième sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 4 - Personnel Enseignant
     */
    public function savePersonnel(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Répartition par spécialité
            'titulaires_hommes' => 'nullable|integer|min:0',
            'titulaires_femmes' => 'nullable|integer|min:0',
            'vacataires_hommes' => 'nullable|integer|min:0',
            'vacataires_femmes' => 'nullable|integer|min:0',
            'volontaires_hommes' => 'nullable|integer|min:0',
            'volontaires_femmes' => 'nullable|integer|min:0',
            'contractuels_hommes' => 'nullable|integer|min:0',
            'contractuels_femmes' => 'nullable|integer|min:0',
            'communautaires_hommes' => 'nullable|integer|min:0',
            'communautaires_femmes' => 'nullable|integer|min:0',
            
            // Répartition par corps
            'instituteurs_hommes' => 'nullable|integer|min:0',
            'instituteurs_femmes' => 'nullable|integer|min:0',
            'instituteurs_adjoints_hommes' => 'nullable|integer|min:0',
            'instituteurs_adjoints_femmes' => 'nullable|integer|min:0',
            'professeurs_hommes' => 'nullable|integer|min:0',
            'professeurs_femmes' => 'nullable|integer|min:0',
            
            // Répartition par diplômes
            'bac_hommes' => 'nullable|integer|min:0',
            'bac_femmes' => 'nullable|integer|min:0',
            'bfem_hommes' => 'nullable|integer|min:0',
            'bfem_femmes' => 'nullable|integer|min:0',
            'cfee_hommes' => 'nullable|integer|min:0',
            'cfee_femmes' => 'nullable|integer|min:0',
            'licence_hommes' => 'nullable|integer|min:0',
            'licence_femmes' => 'nullable|integer|min:0',
            'master_hommes' => 'nullable|integer|min:0',
            'master_femmes' => 'nullable|integer|min:0',
            'autres_diplomes_hommes' => 'nullable|integer|min:0',
            'autres_diplomes_femmes' => 'nullable|integer|min:0',
            
            // Compétences TIC
            'enseignants_formes_tic_hommes' => 'nullable|integer|min:0',
            'enseignants_formes_tic_femmes' => 'nullable|integer|min:0',
        ]);

        // Convertir les valeurs null en 0 pour éviter les erreurs de base de données
        $cleanedData = array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $validated);

        // Les totaux et ratios seront calculés automatiquement par le modèle
        $rapport->personnelEnseignant()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $cleanedData
        );

        return response()->json(['success' => true, 'message' => 'Données Personnel Enseignant sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 5 - Matériel Didactique
     */
    public function saveMateriel(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Dictionnaires
            'dico_francais_total' => 'nullable|integer|min:0',
            'dico_francais_bon_etat' => 'nullable|integer|min:0',
            'dico_arabe_total' => 'nullable|integer|min:0',
            'dico_arabe_bon_etat' => 'nullable|integer|min:0',
            'autres_dico_total' => 'nullable|integer|min:0',
            'autres_dico_bon_etat' => 'nullable|integer|min:0',
            
            // Géométrie
            'regle_total' => 'nullable|integer|min:0',
            'regle_bon_etat' => 'nullable|integer|min:0',
            'equerre_total' => 'nullable|integer|min:0',
            'equerre_bon_etat' => 'nullable|integer|min:0',
            'compas_total' => 'nullable|integer|min:0',
            'compas_bon_etat' => 'nullable|integer|min:0',
            'rapporteur_total' => 'nullable|integer|min:0',
            'rapporteur_bon_etat' => 'nullable|integer|min:0',
            
            // Mesure
            'decametre_total' => 'nullable|integer|min:0',
            'decametre_bon_etat' => 'nullable|integer|min:0',
            'chaine_arpenteur_total' => 'nullable|integer|min:0',
            'chaine_arpenteur_bon_etat' => 'nullable|integer|min:0',
            'boussole_total' => 'nullable|integer|min:0',
            'boussole_bon_etat' => 'nullable|integer|min:0',
            'thermometre_total' => 'nullable|integer|min:0',
            'thermometre_bon_etat' => 'nullable|integer|min:0',
            'kit_capacite_total' => 'nullable|integer|min:0',
            'kit_capacite_bon_etat' => 'nullable|integer|min:0',
            'balance_total' => 'nullable|integer|min:0',
            'balance_bon_etat' => 'nullable|integer|min:0',
            
            // Pédagogique
            'globe_total' => 'nullable|integer|min:0',
            'globe_bon_etat' => 'nullable|integer|min:0',
            'cartes_murales_total' => 'nullable|integer|min:0',
            'cartes_murales_bon_etat' => 'nullable|integer|min:0',
            'planches_illustrees_total' => 'nullable|integer|min:0',
            'planches_illustrees_bon_etat' => 'nullable|integer|min:0',
            'kit_scientifique_total' => 'nullable|integer|min:0',
            'kit_scientifique_bon_etat' => 'nullable|integer|min:0',
            'autres_materiel_total' => 'nullable|integer|min:0',
            'autres_materiel_bon_etat' => 'nullable|integer|min:0',
        ]);

        // Convertir les valeurs null en 0 pour éviter les erreurs de base de données
        $cleanedData = array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $validated);

        // Les totaux et ratios seront calculés automatiquement par l'Observer
        $rapport->materielDidactique()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $cleanedData
        );

        return response()->json(['success' => true, 'message' => 'Données Matériel Didactique sauvegardées']);
    }

    /**
     * Sauvegarder ÉTAPE 5 - Matériel Didactique (Section spécifique)
     */
    public function saveMaterielDidactique(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Dictionnaires
            'dico_francais_total' => 'nullable|integer|min:0',
            'dico_francais_bon_etat' => 'nullable|integer|min:0',
            'dico_arabe_total' => 'nullable|integer|min:0',
            'dico_arabe_bon_etat' => 'nullable|integer|min:0',
            'dico_autre_total' => 'nullable|integer|min:0',
            'dico_autre_bon_etat' => 'nullable|integer|min:0',
            
            // Géométrie
            'regle_plate_total' => 'nullable|integer|min:0',
            'regle_plate_bon_etat' => 'nullable|integer|min:0',
            'equerre_total' => 'nullable|integer|min:0',
            'equerre_bon_etat' => 'nullable|integer|min:0',
            'compas_total' => 'nullable|integer|min:0',
            'compas_bon_etat' => 'nullable|integer|min:0',
            'rapporteur_total' => 'nullable|integer|min:0',
            'rapporteur_bon_etat' => 'nullable|integer|min:0',
            
            // Mesure
            'decametre_total' => 'nullable|integer|min:0',
            'decametre_bon_etat' => 'nullable|integer|min:0',
            'chaine_arpenteur_total' => 'nullable|integer|min:0',
            'chaine_arpenteur_bon_etat' => 'nullable|integer|min:0',
            'boussole_total' => 'nullable|integer|min:0',
            'boussole_bon_etat' => 'nullable|integer|min:0',
            'thermometre_total' => 'nullable|integer|min:0',
            'thermometre_bon_etat' => 'nullable|integer|min:0',
            'kit_capacite_total' => 'nullable|integer|min:0',
            'kit_capacite_bon_etat' => 'nullable|integer|min:0',
            'balance_total' => 'nullable|integer|min:0',
            'balance_bon_etat' => 'nullable|integer|min:0',
            
            // Pédagogique
            'globe_total' => 'nullable|integer|min:0',
            'globe_bon_etat' => 'nullable|integer|min:0',
            'cartes_murales_total' => 'nullable|integer|min:0',
            'cartes_murales_bon_etat' => 'nullable|integer|min:0',
            'planches_illustrees_total' => 'nullable|integer|min:0',
            'planches_illustrees_bon_etat' => 'nullable|integer|min:0',
            'kit_materiel_scientifique_total' => 'nullable|integer|min:0',
            'kit_materiel_scientifique_bon_etat' => 'nullable|integer|min:0',
            'autres_materiel_total' => 'nullable|integer|min:0',
            'autres_materiel_bon_etat' => 'nullable|integer|min:0',
        ]);

        // Convertir les valeurs null en 0
        $cleanedData = array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $validated);

        $rapport->materielDidactique()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $cleanedData
        );

        return response()->json(['success' => true, 'message' => 'Matériel Didactique sauvegardé']);
    }

    /**
     * Sauvegarder ÉTAPE 5 - Manuels Élèves
     */
    public function saveManuelsEleves(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            'manuels' => 'required|array',
            'manuels.*' => 'array',
            'manuels.*.lc_francais' => 'nullable|integer|min:0',
            'manuels.*.mathematiques' => 'nullable|integer|min:0', 
            'manuels.*.edd' => 'nullable|integer|min:0',
            'manuels.*.dm' => 'nullable|integer|min:0',
            'manuels.*.manuel_classe' => 'nullable|integer|min:0',
            'manuels.*.livret_maison' => 'nullable|integer|min:0',
            'manuels.*.livret_devoir_gradue' => 'nullable|integer|min:0',
            'manuels.*.planche_alphabetique' => 'nullable|integer|min:0',
            'manuels.*.manuel_arabe' => 'nullable|integer|min:0',
            'manuels.*.manuel_religion' => 'nullable|integer|min:0',
            'manuels.*.autre_religion' => 'nullable|integer|min:0',
            'manuels.*.autres_manuels' => 'nullable|integer|min:0',
        ]);

        // Sauvegarder chaque niveau
        foreach ($validated['manuels'] as $niveau => $data) {
            // Convertir les valeurs null en 0
            $cleanedData = array_map(function ($value) {
                return $value === null ? 0 : $value;
            }, $data);
            
            $cleanedData['niveau'] = $niveau;

            $rapport->manuelsEleves()->updateOrCreate(
                [
                    'rapport_id' => $rapport->id,
                    'niveau' => $niveau
                ],
                $cleanedData
            );
        }

        return response()->json(['success' => true, 'message' => 'Manuels Élèves sauvegardés']);
    }

    /**
     * Sauvegarder ÉTAPE 5 - Manuels Maître
     */
    public function saveManuelsMaitre(Request $request, Rapport $rapport)
    {
        // Validation de la structure imbriquée: manuels_maitre[niveau][guide]
        $validated = $request->validate([
            'manuels_maitre' => 'required|array',
            'manuels_maitre.*.guide_lc_francais' => 'nullable|integer|min:0',
            'manuels_maitre.*.guide_mathematiques' => 'nullable|integer|min:0',
            'manuels_maitre.*.guide_edd' => 'nullable|integer|min:0',
            'manuels_maitre.*.guide_dm' => 'nullable|integer|min:0',
            'manuels_maitre.*.guide_pedagogique' => 'nullable|integer|min:0',
            'manuels_maitre.*.guide_arabe_religieux' => 'nullable|integer|min:0',
            'manuels_maitre.*.guide_langue_nationale' => 'nullable|integer|min:0',
            'manuels_maitre.*.cahier_recits' => 'nullable|integer|min:0',
            'manuels_maitre.*.autres_manuels_maitre' => 'nullable|integer|min:0',
        ]);

        // Traiter chaque niveau
        foreach ($validated['manuels_maitre'] as $niveau => $guides) {
            // Convertir les valeurs null en 0
            $cleanedData = array_map(function ($value) {
                return $value === null ? 0 : $value;
            }, $guides);

            // Ajouter le niveau au tableau de données
            $cleanedData['niveau'] = $niveau;

            // Créer ou mettre à jour l'enregistrement pour ce niveau
            $rapport->manuelsMaitre()->updateOrCreate(
                [
                    'rapport_id' => $rapport->id,
                    'niveau' => $niveau
                ],
                $cleanedData
            );
        }

        return response()->json(['success' => true, 'message' => 'Manuels Maître sauvegardés']);
    }

    /**
     * Sauvegarder ÉTAPE 6 - Capital Immobilier
     */
    public function saveInfrastructure(Request $request, Rapport $rapport)
    {
        $etablissement = Auth::user()->etablissement;
        $anneeScolaireActive = AnneeScolaire::getActive();
        
        $rapport = Rapport::where('etablissement_id', $etablissement->id)
            ->where('annee_scolaire', $anneeScolaireActive->annee)
            ->first();
            
        if (!$rapport) {
            return response()->json(['error' => 'Rapport non trouvé'], 404);
        }

        $validated = $request->validate([
            'salles_dur' => 'nullable|integer|min:0',
            'salles_hangar' => 'nullable|integer|min:0',
            'salles_provisoires' => 'nullable|integer|min:0',
            'bureau_directeur' => 'nullable|in:existe,inexistant',
            'magasin' => 'nullable|in:existe,inexistant',
            'logement_instituteur' => 'nullable|in:existe,inexistant',
            'latrines' => 'nullable|integer|min:0',
            'urinoirs' => 'nullable|integer|min:0',
            'points_eau' => 'nullable|integer|min:0',
            'terrain_sport' => 'nullable|in:existe,inexistant',
            'aire_jeux' => 'nullable|in:existe,inexistant',
            'cloture' => 'nullable|in:complete,partielle,inexistante',
            'portail' => 'nullable|in:existe,inexistant',
            'etat_general' => 'nullable|in:excellent,bon,moyen,mauvais',
            'besoins_prioritaires' => 'nullable|in:construction,rehabilitation,extension,equipements,aucun',
            'observations_immobilier' => 'nullable|string'
        ]);

        $rapport->capitalImmobilier()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Capital Immobilier sauvegardé']);
    }

    /**
     * Sauvegarder ÉTAPE 6 - Capital Mobilier
     */
    public function saveMobilier(Request $request, Rapport $rapport)
    {
        $etablissement = Auth::user()->etablissement;
        $anneeScolaireActive = AnneeScolaire::getActive();
        
        $rapport = Rapport::where('etablissement_id', $etablissement->id)
            ->where('annee_scolaire', $anneeScolaireActive->annee)
            ->first();
            
        if (!$rapport) {
            return response()->json(['error' => 'Rapport non trouvé'], 404);
        }

        $validated = $request->validate([
            'tables_bancs_total' => 'nullable|integer|min:0',
            'tables_bancs_bon_etat' => 'nullable|integer|min:0',
            'chaises_eleves_total' => 'nullable|integer|min:0',
            'chaises_eleves_bon_etat' => 'nullable|integer|min:0',
            'tables_individuelles_total' => 'nullable|integer|min:0',
            'tables_individuelles_bon_etat' => 'nullable|integer|min:0',
            'bureaux_maitre_total' => 'nullable|integer|min:0',
            'bureaux_maitre_bon_etat' => 'nullable|integer|min:0',
            'chaises_maitre_total' => 'nullable|integer|min:0',
            'chaises_maitre_bon_etat' => 'nullable|integer|min:0',
            'tableaux_total' => 'nullable|integer|min:0',
            'tableaux_bon_etat' => 'nullable|integer|min:0',
            'armoires_total' => 'nullable|integer|min:0',
            'armoires_bon_etat' => 'nullable|integer|min:0',
            'etageres_total' => 'nullable|integer|min:0',
            'etageres_bon_etat' => 'nullable|integer|min:0',
            'bancs_total' => 'nullable|integer|min:0',
            'bancs_bon_etat' => 'nullable|integer|min:0',
            'materiel_specialise' => 'nullable|string',
            'etat_general_mobilier' => 'nullable|in:excellent,bon,moyen,mauvais',
            'besoins_mobilier' => 'nullable|in:tables_bancs,bureaux_chaises,tableaux,rangement,reparation,aucun',
            'observations_mobilier' => 'nullable|string'
        ]);

        $rapport->capitalMobilier()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Capital Mobilier sauvegardé']);
    }

    /**
     * Sauvegarder ÉTAPE 6 - Équipements Informatiques
     */
    public function saveEquipements(Request $request, Rapport $rapport)
    {
        $etablissement = Auth::user()->etablissement;
        $anneeScolaireActive = AnneeScolaire::getActive();
        
        $rapport = Rapport::where('etablissement_id', $etablissement->id)
            ->where('annee_scolaire', $anneeScolaireActive->annee)
            ->first();
            
        if (!$rapport) {
            return response()->json(['error' => 'Rapport non trouvé'], 404);
        }

        $validated = $request->validate([
            'ordinateurs_bureau' => 'nullable|integer|min:0',
            'etat_ordinateurs' => 'nullable|in:excellent,bon,moyen,mauvais',
            'ordinateurs_portables' => 'nullable|integer|min:0',
            'tablettes' => 'nullable|integer|min:0',
            'imprimantes' => 'nullable|integer|min:0',
            'scanners' => 'nullable|integer|min:0',
            'projecteurs' => 'nullable|integer|min:0',
            'ecrans_projection' => 'nullable|integer|min:0',
            'televisions' => 'nullable|integer|min:0',
            'systemes_audio' => 'nullable|integer|min:0',
            'tableaux_interactifs' => 'nullable|integer|min:0',
            'appareils_photo' => 'nullable|integer|min:0',
            'connexion_internet' => 'nullable|in:haut_debit,bas_debit,intermittent,inexistant',
            'reseau_wifi' => 'nullable|in:disponible,limite,inexistant',
            'reseau_local' => 'nullable|in:disponible,partiel,inexistant',
            'alimentation_electrique' => 'nullable|in:stable,instable,intermittent,inexistant',
            'logiciels_educatifs' => 'nullable|in:complet,basique,limite,inexistant',
            'formation_informatique' => 'nullable|in:reguliere,occasionnelle,inexistante',
            'maintenance_equipements' => 'nullable|in:reguliere,besoin,inexistante',
            'utilisation_pedagogique' => 'nullable|string',
            'besoins_informatiques' => 'nullable|in:ordinateurs,connectivite,logiciels,formation,maintenance,aucun',
            'budget_estime' => 'nullable|integer|min:0',
            'projets_informatiques' => 'nullable|string'
        ]);

        $rapport->equipementInformatique()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Équipements Informatiques sauvegardés']);
    }

    // ÉTAPE 5 - Méthodes manquantes
    public function saveDictionnaires(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Dictionnaires (nouvelle structure)
            'dico_francais_total' => 'nullable|integer|min:0',
            'dico_francais_bon_etat' => 'nullable|integer|min:0',
            'dico_arabe_total' => 'nullable|integer|min:0',
            'dico_arabe_bon_etat' => 'nullable|integer|min:0',
            'dico_autre_total' => 'nullable|integer|min:0',
            'dico_autre_bon_etat' => 'nullable|integer|min:0',
            
            // Besoins et observations
            'besoins_dictionnaires' => 'nullable|string',
            'budget_estime_dictionnaires' => 'nullable|integer|min:0',
            'observations_dictionnaires' => 'nullable|string'
        ]);

        $rapport->dictionnaires()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'Dictionnaires sauvegardés']);
    }

    // ÉTAPE 6 - Méthodes avec noms corrects
    public function saveCapitalImmobilier(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Salles de classe
            'salles_dur_total' => 'nullable|integer|min:0',
            'salles_dur_bon_etat' => 'nullable|integer|min:0',
            'abris_provisoires_total' => 'nullable|integer|min:0',
            'abris_provisoires_bon_etat' => 'nullable|integer|min:0',
            
            // Bâtiments administratifs
            'bloc_admin_total' => 'nullable|integer|min:0',
            'bloc_admin_bon_etat' => 'nullable|integer|min:0',
            'magasin_total' => 'nullable|integer|min:0',
            'magasin_bon_etat' => 'nullable|integer|min:0',
            
            // Salles spécialisées
            'salle_informatique_total' => 'nullable|integer|min:0',
            'salle_informatique_bon_etat' => 'nullable|integer|min:0',
            'salle_bibliotheque_total' => 'nullable|integer|min:0',
            'salle_bibliotheque_bon_etat' => 'nullable|integer|min:0',
            'cuisine_total' => 'nullable|integer|min:0',
            'cuisine_bon_etat' => 'nullable|integer|min:0',
            'refectoire_total' => 'nullable|integer|min:0',
            'refectoire_bon_etat' => 'nullable|integer|min:0',
            
            // Toilettes
            'toilettes_enseignants_total' => 'nullable|integer|min:0',
            'toilettes_enseignants_bon_etat' => 'nullable|integer|min:0',
            'toilettes_garcons_total' => 'nullable|integer|min:0',
            'toilettes_garcons_bon_etat' => 'nullable|integer|min:0',
            'toilettes_filles_total' => 'nullable|integer|min:0',
            'toilettes_filles_bon_etat' => 'nullable|integer|min:0',
            'toilettes_mixtes_total' => 'nullable|integer|min:0',
            'toilettes_mixtes_bon_etat' => 'nullable|integer|min:0',
            
            // Logements
            'logement_directeur_total' => 'nullable|integer|min:0',
            'logement_directeur_bon_etat' => 'nullable|integer|min:0',
            'logement_gardien_total' => 'nullable|integer|min:0',
            'logement_gardien_bon_etat' => 'nullable|integer|min:0',
            
            // Autres
            'autres_infrastructures_total' => 'nullable|integer|min:0',
            'autres_infrastructures_bon_etat' => 'nullable|integer|min:0',
        ]);

        // Convertir les valeurs null en 0
        $cleanedData = array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $validated);

        $rapport->capitalImmobilier()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $cleanedData
        );

        return response()->json(['success' => true, 'message' => 'Capital Immobilier sauvegardé']);
    }

    public function saveCapitalMobilier(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Mobilier élèves
            'tables_bancs_total' => 'nullable|integer|min:0',
            'tables_bancs_bon_etat' => 'nullable|integer|min:0',
            'chaises_eleves_total' => 'nullable|integer|min:0',
            'chaises_eleves_bon_etat' => 'nullable|integer|min:0',
            'tables_individuelles_total' => 'nullable|integer|min:0',
            'tables_individuelles_bon_etat' => 'nullable|integer|min:0',
            
            // Mobilier enseignants
            'bureaux_maitre_total' => 'nullable|integer|min:0',
            'bureaux_maitre_bon_etat' => 'nullable|integer|min:0',
            'chaises_maitre_total' => 'nullable|integer|min:0',
            'chaises_maitre_bon_etat' => 'nullable|integer|min:0',
            'tableaux_total' => 'nullable|integer|min:0',
            'tableaux_bon_etat' => 'nullable|integer|min:0',
            'armoires_total' => 'nullable|integer|min:0',
            'armoires_bon_etat' => 'nullable|integer|min:0',
            
            // Mobilier administratif
            'bureaux_admin_total' => 'nullable|integer|min:0',
            'bureaux_admin_bon_etat' => 'nullable|integer|min:0',
            'chaises_admin_total' => 'nullable|integer|min:0',
            'chaises_admin_bon_etat' => 'nullable|integer|min:0',
        ]);

        // Convertir les valeurs null en 0
        $cleanedData = array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $validated);

        $rapport->capitalMobilier()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $cleanedData
        );

        return response()->json(['success' => true, 'message' => 'Capital Mobilier sauvegardé']);
    }

    public function saveEquipementsInformatiques(Request $request, Rapport $rapport)
    {
        $validated = $request->validate([
            // Ordinateurs
            'ordinateurs_fixes_total' => 'nullable|integer|min:0',
            'ordinateurs_fixes_bon_etat' => 'nullable|integer|min:0',
            'ordinateurs_portables_total' => 'nullable|integer|min:0',
            'ordinateurs_portables_bon_etat' => 'nullable|integer|min:0',
            'tablettes_total' => 'nullable|integer|min:0',
            'tablettes_bon_etat' => 'nullable|integer|min:0',
            
            // Imprimantes
            'imprimantes_laser_total' => 'nullable|integer|min:0',
            'imprimantes_laser_bon_etat' => 'nullable|integer|min:0',
            'imprimantes_jet_encre_total' => 'nullable|integer|min:0',
            'imprimantes_jet_encre_bon_etat' => 'nullable|integer|min:0',
            'imprimantes_multifonction_total' => 'nullable|integer|min:0',
            'imprimantes_multifonction_bon_etat' => 'nullable|integer|min:0',
            'photocopieuses_total' => 'nullable|integer|min:0',
            'photocopieuses_bon_etat' => 'nullable|integer|min:0',
            
            // Équipement audiovisuel
            'videoprojecteurs_total' => 'nullable|integer|min:0',
            'videoprojecteurs_bon_etat' => 'nullable|integer|min:0',
            'autres_equipements_total' => 'nullable|integer|min:0',
            'autres_equipements_bon_etat' => 'nullable|integer|min:0',
        ]);

        // Convertir les valeurs null en 0
        $cleanedData = array_map(function ($value) {
            return $value === null ? 0 : $value;
        }, $validated);

        $rapport->equipementInformatique()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $cleanedData
        );

        return response()->json(['success' => true, 'message' => 'Équipements Informatiques sauvegardés']);
    }

    /**
     * Soumettre le rapport pour validation
     */
    public function submit(Request $request, Rapport $rapport)
    {
        // Vérifier que le rapport appartient à l'établissement de l'utilisateur
        if ($rapport->etablissement_id !== Auth::user()->etablissement_id) {
            abort(403);
        }

        // Vérifier que le rapport est modifiable
        if (!$rapport->estModifiable()) {
            return back()->with('error', 'Ce rapport ne peut plus être modifié.');
        }

        // Valider le commentaire (optionnel)
        $request->validate([
            'commentaire_etablissement' => 'nullable|string|max:1000'
        ]);

        // Mettre à jour le statut
        $rapport->update([
            'statut' => 'soumis',
            'date_soumission' => now(),
            'submitted_by' => Auth::id(),
            'submitted_at' => now(),
            'commentaire_validation' => $request->commentaire_etablissement
        ]);

        // Enregistrer dans l'historique
        \App\Models\RapportHistorique::create([
            'rapport_id' => $rapport->id,
            'user_id' => Auth::id(),
            'action' => 'soumission',
            'ancien_statut' => 'brouillon',
            'nouveau_statut' => 'soumis',
            'commentaire' => $request->commentaire_etablissement
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rapport soumis avec succès. Il sera examiné par l\'administration.',
            'redirect' => route('etablissement.rapport-rentree.historique.index')
        ]);
    }
}
