<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;
use App\Services\RapportExcelTemplateService;
use App\Services\RapportExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuiviRapportController extends Controller
{
    /**
     * Display the list of reports with progress
     */
    public function index(Request $request)
    {
        // Récupérer toutes les années disponibles
        $anneesDisponibles = AnneeScolaire::orderBy('annee', 'desc')->get();
        
        // Récupérer l'année scolaire active
        $anneeActive = AnneeScolaire::getActive();
        
        // Déterminer l'année à afficher (filtrée ou active par défaut)
        $anneeSelectionnee = $request->get('annee', $anneeActive?->annee);
        
        if (!$anneeSelectionnee) {
            return back()->with('error', 'Aucune année scolaire disponible. Veuillez créer une année dans la gestion des années scolaires.');
        }

        // Construire la query des rapports avec eager loading
        $query = Etablissement::with(['rapports' => function($q) use ($anneeSelectionnee, $request) {
            $q->where('annee_scolaire', $anneeSelectionnee);
            
            // Si un statut est filtré, ne charger que les rapports avec ce statut
            if ($request->filled('statut_rapport')) {
                $q->where('statut', $request->statut_rapport);
            }
            
            $q->with([
                'infoDirecteur',
                'infrastructuresBase',
                'structuresCommunautaires',
                'languesProjets',
                'ressourcesFinancieres',
                'effectifs',
                'cmg',
                'cfee',
                'entreeSixieme',
                'recrutementCi',
                'personnelEnseignant',
                'manuelsEleves',
                'manuelsMaitre',
                'dictionnaires',
                'materielDidactique',
                'capitalImmobilier',
                'capitalMobilier',
                'equipementInformatique'
            ])->latest();
        }])
        // Filtrer seulement les établissements qui ONT un rapport pour l'année sélectionnée
        ->whereHas('rapports', function($q) use ($anneeSelectionnee, $request) {
            $q->where('annee_scolaire', $anneeSelectionnee);
            
            // Appliquer le filtre de statut si présent
            if ($request->filled('statut_rapport')) {
                $q->where('statut', $request->statut_rapport);
            }
        });

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('etablissement', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('commune')) {
            $query->where('commune', $request->commune);
        }

        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }

        // Le filtre de statut est déjà appliqué dans le whereHas ci-dessus

        $etablissements = $query->orderBy('etablissement')->paginate(20)->withQueryString();

        // Calculer la progression pour chaque établissement
        foreach ($etablissements as $etablissement) {
            if ($etablissement->rapports->first()) {
                $rapport = $etablissement->rapports->first();
                $rapport->progression_data = $this->calculateProgress($rapport);
            }
        }

        // Statistiques globales pour l'année sélectionnée
        $stats = [
            'total' => Etablissement::count(),
            'avec_rapport' => Etablissement::whereHas('rapports', function($q) use ($anneeSelectionnee) {
                $q->where('annee_scolaire', $anneeSelectionnee);
            })->count(),
            'sans_rapport' => Etablissement::whereDoesntHave('rapports', function($q) use ($anneeSelectionnee) {
                $q->where('annee_scolaire', $anneeSelectionnee);
            })->count(),
            'brouillons' => Rapport::where('annee_scolaire', $anneeSelectionnee)->where('statut', 'brouillon')->count(),
            'soumis' => Rapport::where('annee_scolaire', $anneeSelectionnee)->where('statut', 'soumis')->count(),
            'valides' => Rapport::where('annee_scolaire', $anneeSelectionnee)->where('statut', 'validé')->count(),
            'rejetes' => Rapport::where('annee_scolaire', $anneeSelectionnee)->where('statut', 'rejeté')->count(),
        ];

        // Listes pour les filtres (basées sur l'année sélectionnée ET les filtres actifs)
        // Ces listes montrent uniquement les options disponibles selon les filtres déjà appliqués
        $listsQuery = Etablissement::whereHas('rapports', function($q) use ($anneeSelectionnee, $request) {
            $q->where('annee_scolaire', $anneeSelectionnee);
            
            // Appliquer le filtre de statut si présent
            if ($request->filled('statut_rapport')) {
                $q->where('statut', $request->statut_rapport);
            }
        });
        
        // Appliquer les filtres existants pour affiner les listes
        if ($request->filled('commune')) {
            $listsQueryWithCommune = clone $listsQuery;
            $listsQueryWithCommune->where('commune', $request->commune);
        }
        
        if ($request->filled('zone')) {
            $listsQueryWithZone = clone $listsQuery;
            $listsQueryWithZone->where('zone', $request->zone);
        }

        $lists = [
            'communes' => (clone $listsQuery)
                ->whereNotNull('commune')
                ->distinct()
                ->orderBy('commune')
                ->pluck('commune')
                ->toArray(),
            'zones' => (clone $listsQuery)
                ->whereNotNull('zone')
                ->distinct()
                ->orderBy('zone')
                ->pluck('zone')
                ->toArray(),
            'statuts' => Rapport::where('annee_scolaire', $anneeSelectionnee)
                ->when($request->filled('commune'), function($q) use ($request) {
                    $q->whereHas('etablissement', function($eq) use ($request) {
                        $eq->where('commune', $request->commune);
                    });
                })
                ->when($request->filled('zone'), function($q) use ($request) {
                    $q->whereHas('etablissement', function($eq) use ($request) {
                        $eq->where('zone', $request->zone);
                    });
                })
                ->distinct()
                ->pluck('statut')
                ->toArray()
        ];

        return view('admin.suivi-rapports.index', compact('etablissements', 'stats', 'lists', 'anneesDisponibles', 'anneeSelectionnee', 'anneeActive'));
    }

    /**
     * Télécharger le template Excel vierge pour import de rapports
     */
    public function downloadExcelTemplate()
    {
        \Log::info('=== DOWNLOAD TEMPLATE START ===');
        \Log::info('Request URL: ' . request()->fullUrl());
        \Log::info('Request method: ' . request()->method());
        
        try {
            \Log::info('Creating RapportExcelTemplateService...');
            $service = new RapportExcelTemplateService();
            
            \Log::info('Calling generateTemplate()...');
            $service->generateTemplate();
            \Log::info('Template generated successfully');
            
            $filename = 'template_rapport_rentree_' . date('Y-m-d') . '.xlsx';
            \Log::info('Filename: ' . $filename);
            
            // Vérifier le répertoire temporaire
            $tempDir = sys_get_temp_dir();
            \Log::info('Temp directory: ' . $tempDir);
            \Log::info('Temp directory writable: ' . (is_writable($tempDir) ? 'YES' : 'NO'));
            
            \Log::info('Calling downloadTemplate()...');
            $response = $service->downloadTemplate($filename);
            \Log::info('Download response created successfully');
            \Log::info('Response type: ' . get_class($response));
            
            return $response;
        } catch (\Exception $e) {
            \Log::error('=== DOWNLOAD TEMPLATE ERROR ===');
            \Log::error('Erreur génération template Excel', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors de la génération du template : ' . $e->getMessage());
        }
    }

    /**
     * Importer des rapports depuis un fichier Excel
     */
    public function importExcel(Request $request)
    {
        try {
            // Validation du fichier
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10 MB max
                'etablissement_id' => 'required|exists:etablissements,id',
                'annee_scolaire' => 'required|string'
            ], [
                'excel_file.required' => 'Veuillez sélectionner un fichier Excel.',
                'excel_file.mimes' => 'Le fichier doit être au format Excel (.xlsx ou .xls).',
                'excel_file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
                'etablissement_id.required' => 'Veuillez sélectionner un établissement.',
                'etablissement_id.exists' => 'L\'établissement sélectionné n\'existe pas.',
                'annee_scolaire.required' => 'Veuillez sélectionner une année scolaire.'
            ]);
            
            Log::info('=== IMPORT EXCEL DÉMARRÉ ===', [
                'etablissement_id' => $request->etablissement_id,
                'annee_scolaire' => $request->annee_scolaire,
                'fichier' => $request->file('excel_file')->getClientOriginalName()
            ]);
            
            // Récupérer le fichier uploadé
            $file = $request->file('excel_file');
            $filePath = $file->getRealPath();
            
            // Importer via le service
            $importService = new RapportExcelImportService();
            $result = $importService->importFromExcel(
                $filePath,
                $request->etablissement_id,
                $request->annee_scolaire
            );
            
            if ($result['success']) {
                $message = 'Import réussi ! Le rapport a été créé avec succès.';
                
                if (count($result['warnings']) > 0) {
                    $message .= ' (' . count($result['warnings']) . ' avertissement(s) détecté(s))';
                }
                
                Log::info('Import Excel réussi', [
                    'rapport_id' => $result['rapport']->id,
                    'warnings_count' => count($result['warnings'])
                ]);
                
                return back()->with('success', $message)
                            ->with('warnings', $result['warnings']);
            } else {
                Log::error('Import Excel échoué', ['erreurs' => $result['errors']]);
                
                return back()->with('error', 'Erreurs lors de l\'import :')
                            ->with('import_errors', $result['errors'])
                            ->with('warnings', $result['warnings']);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur import Excel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }

    /**
     * Display detailed view of a report
     */
    public function show(Rapport $rapport)
    {
        $rapport->load([
            'etablissement',
            'infoDirecteur',
            'infrastructuresBase',
            'structuresCommunautaires',
            'languesProjets',
            'ressourcesFinancieres',
            'effectifs',
            'cmg',
            'cfee',
            'entreeSixieme',
            'recrutementCi',
            'personnelEnseignant',
            'manuelsEleves',
            'manuelsMaitre',
            'dictionnaires',
            'materielDidactique',
            'capitalImmobilier',
            'capitalMobilier',
            'equipementInformatique',
            'historique'
        ]);

        // Calculer la progression
        $progression = $this->calculateProgress($rapport);

        return view('admin.suivi-rapports.show', compact('rapport', 'progression'));
    }

    /**
     * Validate a report
     */
    public function valider(Request $request, Rapport $rapport)
    {
        $request->validate([
            'commentaire_admin' => 'nullable|string|max:500'
        ]);

        DB::transaction(function() use ($rapport, $request) {
            $rapport->update([
                'statut' => 'validé',
                'date_validation' => now(),
                'validated_by' => auth()->id(),
                'validated_at' => now(),
                'commentaire_admin' => $request->commentaire_admin
            ]);

            // Enregistrer dans l'historique
            \App\Models\RapportHistorique::create([
                'rapport_id' => $rapport->id,
                'user_id' => auth()->id(),
                'action' => 'validation',
                'ancien_statut' => 'soumis',
                'nouveau_statut' => 'validé',
                'commentaire' => $request->commentaire_admin
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Rapport validé avec succès'
        ]);
    }

    /**
     * Reject a report
     */
    public function rejeter(Request $request, Rapport $rapport)
    {
        $request->validate([
            'motif_rejet' => 'required|string|max:500'
        ]);

        DB::transaction(function() use ($rapport, $request) {
            $rapport->update([
                'statut' => 'rejeté',
                'date_rejet' => now(),
                'motif_rejet' => $request->motif_rejet
            ]);

            // Enregistrer dans l'historique
            \App\Models\RapportHistorique::create([
                'rapport_id' => $rapport->id,
                'user_id' => auth()->id(),
                'action' => 'rejet',
                'ancien_statut' => 'soumis',
                'nouveau_statut' => 'rejeté',
                'commentaire' => $request->motif_rejet
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Rapport rejeté. L\'établissement peut le modifier.'
        ]);
    }

    /**
     * Calculate progress for a report based on actual data completion
     * Une sous-section est considérée complète si elle contient AU MOINS quelques données
     * CORRIGÉ: Basé sur la structure RÉELLE des tables MySQL
     */
    private function calculateProgress(Rapport $rapport)
    {
        // ÉTAPE 1: Informations Générales (5 sous-sections)
        $etape1_info_directeur = $rapport->infoDirecteur !== null && 
            (!empty($rapport->infoDirecteur->directeur_nom) || 
             !empty($rapport->infoDirecteur->directeur_contact_1) ||
             !empty($rapport->infoDirecteur->directeur_email));
        
        $etape1_infrastructures = $rapport->infrastructuresBase !== null && 
            ($rapport->infrastructuresBase->cpe_existe == 1 || 
             $rapport->infrastructuresBase->cloture_existe == 1 || 
             $rapport->infrastructuresBase->eau_existe == 1 ||
             $rapport->infrastructuresBase->electricite_existe == 1 ||
             $rapport->infrastructuresBase->cantine_existe == 1);
        
        $etape1_structures = $rapport->structuresCommunautaires !== null && 
            ($rapport->structuresCommunautaires->cge_existe == 1 || 
             $rapport->structuresCommunautaires->gscol_existe == 1 ||
             $rapport->structuresCommunautaires->ape_existe == 1 ||
             $rapport->structuresCommunautaires->cge_total > 0);
        
        $etape1_langues = $rapport->languesProjets !== null && 
            (!empty($rapport->languesProjets->langue_nationale) ||
             $rapport->languesProjets->enseignement_arabe_existe == 1 ||
             $rapport->languesProjets->projets_informatiques_existe == 1);
        
        $etape1_finances = $rapport->ressourcesFinancieres !== null && 
            ($rapport->ressourcesFinancieres->subvention_etat_montant > 0 || 
             $rapport->ressourcesFinancieres->subvention_partenaires_montant > 0 ||
             $rapport->ressourcesFinancieres->subvention_collectivites_montant > 0 ||
             $rapport->ressourcesFinancieres->subvention_etat_existe == 1);

        // ÉTAPE 2: Effectifs (6 sous-sections)
        $hasEffectifs = $rapport->effectifs && $rapport->effectifs->count() > 0;
        
        $etape2_classes = $hasEffectifs && 
            $rapport->effectifs->sum('nombre_classes') > 0;
        
        $etape2_effectifs_totaux = $hasEffectifs && 
            ($rapport->effectifs->sum('effectif_garcons') > 0 || 
             $rapport->effectifs->sum('effectif_filles') > 0 ||
             $rapport->effectifs->sum('effectif_total') > 0);
        
        $etape2_redoublants = $hasEffectifs && 
            ($rapport->effectifs->sum('redoublants_garcons') > 0 ||
             $rapport->effectifs->sum('redoublants_filles') > 0 ||
             $rapport->effectifs->sum('redoublants_total') > 0);
        
        $etape2_abandons = $hasEffectifs && 
            ($rapport->effectifs->sum('abandons_garcons') > 0 ||
             $rapport->effectifs->sum('abandons_filles') > 0 ||
             $rapport->effectifs->sum('abandons_total') > 0);
        
        $etape2_handicaps = $hasEffectifs && 
            ($rapport->effectifs->sum('handicap_moteur_total') > 0 ||
             $rapport->effectifs->sum('handicap_visuel_total') > 0 ||
             $rapport->effectifs->sum('handicap_sourd_muet_total') > 0 ||
             $rapport->effectifs->sum('handicap_deficience_intel_total') > 0);
        
        $etape2_situations = $hasEffectifs && 
            ($rapport->effectifs->sum('orphelins_total') > 0 ||
             $rapport->effectifs->sum('sans_extrait_total') > 0);

        // ÉTAPE 3: Examens (4 sous-sections)
        $etape3_cmg = $rapport->cmg !== null && 
            ($rapport->cmg->cmg_nombre > 0 || 
             !empty($rapport->cmg->cmg_combinaison_1) ||
             !empty($rapport->cmg->cmg_combinaison_2));
        
        $etape3_cfee = $rapport->cfee !== null && 
            ($rapport->cfee->cfee_candidats_total > 0 || 
             $rapport->cfee->cfee_admis_total > 0 ||
             $rapport->cfee->cfee_taux_reussite > 0);
        
        $etape3_entree_sixieme = $rapport->entreeSixieme !== null && 
            ($rapport->entreeSixieme->sixieme_candidats_total > 0 || 
             $rapport->entreeSixieme->sixieme_admis_total > 0 ||
             $rapport->entreeSixieme->sixieme_taux_admission > 0);
        
        $etape3_recrutement = $rapport->recrutementCi !== null && 
            ($rapport->recrutementCi->ci_nombre > 0 || 
             $rapport->recrutementCi->ci_octobre_garcons > 0 ||
             $rapport->recrutementCi->ci_octobre_filles > 0 ||
             !empty($rapport->recrutementCi->ci_combinaison_1));

        // ÉTAPE 4: Personnel (5 sous-sections)
        $hasPersonnel = $rapport->personnelEnseignant !== null;
        
        $etape4_specialite = $hasPersonnel && 
            ($rapport->personnelEnseignant->titulaires_total > 0 ||
             $rapport->personnelEnseignant->vacataires_total > 0 ||
             $rapport->personnelEnseignant->volontaires_total > 0);
        
        $etape4_corps = $hasPersonnel && 
            ($rapport->personnelEnseignant->instituteurs_total > 0 || 
             $rapport->personnelEnseignant->instituteurs_adjoints_total > 0 ||
             $rapport->personnelEnseignant->professeurs_total > 0);
        
        $etape4_diplomes = $hasPersonnel && 
            ($rapport->personnelEnseignant->bac_total > 0 || 
             $rapport->personnelEnseignant->bfem_total > 0 ||
             $rapport->personnelEnseignant->licence_total > 0 ||
             $rapport->personnelEnseignant->master_total > 0);
        
        $etape4_tic = $hasPersonnel && 
            ($rapport->personnelEnseignant->enseignants_formes_tic_total > 0 ||
             $rapport->personnelEnseignant->enseignants_formes_tic_hommes > 0 ||
             $rapport->personnelEnseignant->enseignants_formes_tic_femmes > 0);
        
        $etape4_statistiques = $hasPersonnel && 
            ($rapport->personnelEnseignant->total_personnel > 0 ||
             $rapport->personnelEnseignant->total_personnel_hommes > 0 ||
             $rapport->personnelEnseignant->total_personnel_femmes > 0);

        // ÉTAPE 5: Matériel Pédagogique (4 sous-sections)
        $etape5_manuels_eleves = $rapport->manuelsEleves && 
            $rapport->manuelsEleves->count() > 0 && 
            ($rapport->manuelsEleves->sum('lc_francais') > 0 ||
             $rapport->manuelsEleves->sum('mathematiques') > 0 ||
             $rapport->manuelsEleves->sum('edd') > 0 ||
             $rapport->manuelsEleves->sum('manuel_classe') > 0);
        
        $etape5_manuels_maitre = $rapport->manuelsMaitre && 
            $rapport->manuelsMaitre->count() > 0 && 
            ($rapport->manuelsMaitre->sum('guide_lc_francais') > 0 ||
             $rapport->manuelsMaitre->sum('guide_mathematiques') > 0 ||
             $rapport->manuelsMaitre->sum('guide_edd') > 0);
        
        $etape5_dictionnaires = $rapport->materielDidactique !== null && 
            ($rapport->materielDidactique->dico_francais_total > 0 || 
             $rapport->materielDidactique->dico_arabe_total > 0 ||
             $rapport->materielDidactique->dico_autre_total > 0);
        
        $etape5_materiel = $rapport->materielDidactique !== null && 
            ($rapport->materielDidactique->globe_total > 0 || 
             $rapport->materielDidactique->cartes_murales_total > 0 ||
             $rapport->materielDidactique->planches_illustrees_total > 0 ||
             $rapport->materielDidactique->kit_materiel_scientifique_total > 0 ||
             $rapport->materielDidactique->regle_plate_total > 0 || 
             $rapport->materielDidactique->equerre_total > 0 ||
             $rapport->materielDidactique->compas_total > 0 ||
             $rapport->materielDidactique->rapporteur_total > 0);

        // ÉTAPE 6: Infrastructure & Équipements (3 sous-sections)
        $etape6_immobilier = $rapport->capitalImmobilier !== null && 
            ($rapport->capitalImmobilier->salles_dur_total > 0 || 
             $rapport->capitalImmobilier->abris_provisoires_total > 0 ||
             $rapport->capitalImmobilier->bloc_admin_total > 0 ||
             $rapport->capitalImmobilier->magasin_total > 0 ||
             $rapport->capitalImmobilier->salle_informatique_total > 0);
        
        $etape6_mobilier = $rapport->capitalMobilier !== null && 
            ($rapport->capitalMobilier->tables_bancs_total > 0 || 
             $rapport->capitalMobilier->chaises_eleves_total > 0 ||
             $rapport->capitalMobilier->bureaux_maitre_total > 0 ||
             $rapport->capitalMobilier->tableaux_total > 0);
        
        $etape6_informatique = $rapport->equipementInformatique !== null && 
            ($rapport->equipementInformatique->ordinateurs_fixes_total > 0 || 
             $rapport->equipementInformatique->ordinateurs_portables_total > 0 ||
             $rapport->equipementInformatique->tablettes_total > 0 ||
             $rapport->equipementInformatique->imprimantes_laser_total > 0 ||
             $rapport->equipementInformatique->videoprojecteurs_total > 0);

        // Construction du tableau des étapes avec détails
        $etapes = [
            // ÉTAPE 1
            'info_directeur' => [
                'nom' => 'Info Directeur',
                'complete' => $etape1_info_directeur,
                'icon' => 'fa-user-tie',
                'color' => 'purple',
                'etape' => 1
            ],
            'infrastructures_base' => [
                'nom' => 'Infrastructures de Base',
                'complete' => $etape1_infrastructures,
                'icon' => 'fa-building',
                'color' => 'orange',
                'etape' => 1
            ],
            'structures_communautaires' => [
                'nom' => 'Structures Communautaires',
                'complete' => $etape1_structures,
                'icon' => 'fa-users',
                'color' => 'blue',
                'etape' => 1
            ],
            'langues_projets' => [
                'nom' => 'Langues & Projets',
                'complete' => $etape1_langues,
                'icon' => 'fa-language',
                'color' => 'pink',
                'etape' => 1
            ],
            'ressources_financieres' => [
                'nom' => 'Ressources Financières',
                'complete' => $etape1_finances,
                'icon' => 'fa-coins',
                'color' => 'green',
                'etape' => 1
            ],
            
            // ÉTAPE 2
            'nombre_classes' => [
                'nom' => 'Nombre de Classes',
                'complete' => $etape2_classes,
                'icon' => 'fa-door-open',
                'color' => 'indigo',
                'etape' => 2
            ],
            'effectifs_totaux' => [
                'nom' => 'Effectifs Totaux',
                'complete' => $etape2_effectifs_totaux,
                'icon' => 'fa-users-cog',
                'color' => 'blue',
                'etape' => 2
            ],
            'redoublants' => [
                'nom' => 'Redoublants',
                'complete' => $etape2_redoublants,
                'icon' => 'fa-redo',
                'color' => 'amber',
                'etape' => 2
            ],
            'abandons' => [
                'nom' => 'Abandons',
                'complete' => $etape2_abandons,
                'icon' => 'fa-user-times',
                'color' => 'red',
                'etape' => 2
            ],
            'handicaps' => [
                'nom' => 'Élèves Handicapés',
                'complete' => $etape2_handicaps,
                'icon' => 'fa-wheelchair',
                'color' => 'teal',
                'etape' => 2
            ],
            'situations_speciales' => [
                'nom' => 'Situations Spéciales',
                'complete' => $etape2_situations,
                'icon' => 'fa-exclamation-triangle',
                'color' => 'yellow',
                'etape' => 2
            ],
            
            // ÉTAPE 3
            'cmg' => [
                'nom' => 'CMG',
                'complete' => $etape3_cmg,
                'icon' => 'fa-users',
                'color' => 'blue',
                'etape' => 3
            ],
            'cfee' => [
                'nom' => 'CFEE',
                'complete' => $etape3_cfee,
                'icon' => 'fa-graduation-cap',
                'color' => 'purple',
                'etape' => 3
            ],
            'entree_sixieme' => [
                'nom' => 'Entrée en 6ème',
                'complete' => $etape3_entree_sixieme,
                'icon' => 'fa-door-open',
                'color' => 'indigo',
                'etape' => 3
            ],
            'recrutement_ci' => [
                'nom' => 'Recrutement CI',
                'complete' => $etape3_recrutement,
                'icon' => 'fa-user-plus',
                'color' => 'green',
                'etape' => 3
            ],
            
            // ÉTAPE 4
            'personnel_specialite' => [
                'nom' => 'Répartition par Spécialité',
                'complete' => $etape4_specialite,
                'icon' => 'fa-users',
                'color' => 'blue',
                'etape' => 4
            ],
            'personnel_corps' => [
                'nom' => 'Répartition par Corps',
                'complete' => $etape4_corps,
                'icon' => 'fa-graduation-cap',
                'color' => 'purple',
                'etape' => 4
            ],
            'personnel_diplomes' => [
                'nom' => 'Répartition par Diplômes',
                'complete' => $etape4_diplomes,
                'icon' => 'fa-certificate',
                'color' => 'amber',
                'etape' => 4
            ],
            'personnel_tic' => [
                'nom' => 'Compétences TIC',
                'complete' => $etape4_tic,
                'icon' => 'fa-laptop',
                'color' => 'cyan',
                'etape' => 4
            ],
            'personnel_statistiques' => [
                'nom' => 'Statistiques Personnel',
                'complete' => $etape4_statistiques,
                'icon' => 'fa-chart-bar',
                'color' => 'green',
                'etape' => 4
            ],
            
            // ÉTAPE 5
            'manuels_eleves' => [
                'nom' => 'Manuels Élèves',
                'complete' => $etape5_manuels_eleves,
                'icon' => 'fa-book-open',
                'color' => 'blue',
                'etape' => 5
            ],
            'manuels_maitre' => [
                'nom' => 'Manuels Maître',
                'complete' => $etape5_manuels_maitre,
                'icon' => 'fa-user-tie',
                'color' => 'purple',
                'etape' => 5
            ],
            'dictionnaires' => [
                'nom' => 'Dictionnaires',
                'complete' => $etape5_dictionnaires,
                'icon' => 'fa-book',
                'color' => 'indigo',
                'etape' => 5
            ],
            'materiel_didactique' => [
                'nom' => 'Matériel Didactique',
                'complete' => $etape5_materiel,
                'icon' => 'fa-graduation-cap',
                'color' => 'green',
                'etape' => 5
            ],
            
            // ÉTAPE 6
            'capital_immobilier' => [
                'nom' => 'Capital Immobilier',
                'complete' => $etape6_immobilier,
                'icon' => 'fa-building-columns',
                'color' => 'orange',
                'etape' => 6
            ],
            'capital_mobilier' => [
                'nom' => 'Capital Mobilier',
                'complete' => $etape6_mobilier,
                'icon' => 'fa-chair',
                'color' => 'amber',
                'etape' => 6
            ],
            'equipement_informatique' => [
                'nom' => 'Équipement Informatique',
                'complete' => $etape6_informatique,
                'icon' => 'fa-laptop',
                'color' => 'cyan',
                'etape' => 6
            ],
        ];

        $total = count($etapes);
        $completes = collect($etapes)->where('complete', true)->count();
        $pourcentage = $total > 0 ? round(($completes / $total) * 100) : 0;

        // Statistiques par étape
        $etapesStats = [];
        for ($i = 1; $i <= 6; $i++) {
            $etapeItems = collect($etapes)->where('etape', $i);
            $etapesStats[$i] = [
                'total' => $etapeItems->count(),
                'completes' => $etapeItems->where('complete', true)->count(),
                'pourcentage' => $etapeItems->count() > 0 
                    ? round(($etapeItems->where('complete', true)->count() / $etapeItems->count()) * 100) 
                    : 0
            ];
        }

        return [
            'etapes' => $etapes,
            'total' => $total,
            'completes' => $completes,
            'pourcentage' => $pourcentage,
            'etapes_stats' => $etapesStats
        ];
    }
}
