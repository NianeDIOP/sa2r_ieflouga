<?php

namespace App\Services;

use App\Models\Rapport;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RapportExcelImportService
{
    private $spreadsheet;
    private $errors = [];
    private $warnings = [];
    private $etablissement;
    private $anneeScolaire;
    
    /**
     * Importer un rapport depuis un fichier Excel
     * 
     * @param string $filePath Chemin du fichier Excel
     * @param int $etablissementId ID de l'établissement
     * @param string $anneeScolaire Année scolaire (ex: 2024-2025)
     * @return array ['success' => bool, 'rapport' => Rapport|null, 'errors' => array, 'warnings' => array]
     */
    public function importFromExcel($filePath, $etablissementId, $anneeScolaire)
    {
        try {
            // Vérifier l'établissement
            $this->etablissement = Etablissement::findOrFail($etablissementId);
            $this->anneeScolaire = $anneeScolaire;
            
            Log::info('=== DÉBUT IMPORT EXCEL ===', [
                'fichier' => $filePath,
                'etablissement' => $this->etablissement->etablissement,
                'annee' => $anneeScolaire
            ]);
            
            // Charger le fichier Excel
            $this->spreadsheet = IOFactory::load($filePath);
            
            // Vérifier la structure du fichier (7 onglets attendus)
            if ($this->spreadsheet->getSheetCount() < 7) {
                throw new Exception("Le fichier Excel doit contenir 7 onglets (Instructions + 6 étapes). Trouvé : " . $this->spreadsheet->getSheetCount());
            }
            
            DB::beginTransaction();
            
            // Créer ou récupérer le rapport
            $rapport = Rapport::firstOrCreate(
                [
                    'etablissement_id' => $etablissementId,
                    'annee_scolaire' => $anneeScolaire
                ],
                [
                    'statut' => 'brouillon',
                    'date_rapport' => now(),
                    'trimestre' => 'annuel'
                ]
            );
            
            Log::info('Rapport créé/récupéré', ['rapport_id' => $rapport->id]);
            
            // Importer chaque section
            $this->importEtape1($rapport); // Infos générales
            $this->importEtape2($rapport); // Effectifs
            $this->importEtape3($rapport); // Examens
            $this->importEtape4($rapport); // Personnel
            $this->importEtape5($rapport); // Matériel pédagogique
            $this->importEtape6($rapport); // Infrastructure
            
            // Si des erreurs critiques, rollback
            if (count($this->errors) > 0) {
                DB::rollBack();
                Log::error('Import échoué avec erreurs', ['erreurs' => $this->errors]);
                
                return [
                    'success' => false,
                    'rapport' => null,
                    'errors' => $this->errors,
                    'warnings' => $this->warnings
                ];
            }
            
            // Mettre à jour le rapport
            $rapport->updated_at = now();
            $rapport->save();
            
            DB::commit();
            
            Log::info('=== IMPORT RÉUSSI ===', [
                'rapport_id' => $rapport->id,
                'warnings' => count($this->warnings)
            ]);
            
            return [
                'success' => true,
                'rapport' => $rapport,
                'errors' => [],
                'warnings' => $this->warnings
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur import Excel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'rapport' => null,
                'errors' => ['Erreur critique : ' . $e->getMessage()],
                'warnings' => $this->warnings
            ];
        }
    }
    
    /**
     * ÉTAPE 1 : Informations Générales
     */
    private function importEtape1($rapport)
    {
        $sheet = $this->spreadsheet->getSheetByName('Etape 1 - Infos');
        if (!$sheet) {
            $this->errors[] = "Onglet 'Etape 1 - Infos' introuvable";
            return;
        }
        
        try {
            // 1.1 - Info Directeur
            $this->importInfoDirecteur($rapport, $sheet);
            
            // 1.2 - Infrastructures de Base
            $this->importInfrastructures($rapport, $sheet);
            
            // 1.3 - Structures Communautaires
            $this->importStructuresCommunautaires($rapport, $sheet);
            
            // 1.4 - Langues et Projets
            $this->importLanguesProjets($rapport, $sheet);
            
            // 1.5 - Ressources Financières
            $this->importRessourcesFinancieres($rapport, $sheet);
            
            Log::info('Étape 1 importée avec succès');
        } catch (Exception $e) {
            $this->errors[] = "Étape 1 : " . $e->getMessage();
        }
    }
    
    private function importInfoDirecteur($rapport, $sheet)
    {
        $nom = $this->getCellValue($sheet, 'B', 5);
        $contactPrincipal = $this->getCellValue($sheet, 'B', 6);
        $contactSecondaire = $this->getCellValue($sheet, 'B', 7);
        $email = $this->getCellValue($sheet, 'B', 8);
        $distance = $this->getCellValue($sheet, 'B', 9, 0);
        
        // Validation du nom avec message détaillé
        if (!$this->validateNom($nom)) {
            if (is_numeric($nom)) {
                $this->errors[] = "Nom du directeur invalide : Un numéro de téléphone a été saisi au lieu du nom (Cellule B5). Veuillez saisir le nom complet.";
            } else {
                $this->errors[] = "Nom du directeur invalide (min 3 caractères, 2 mots minimum, lettres uniquement) - Cellule B5 : '{$nom}'";
            }
        }
        
        // Validation contact principal
        if (!$this->validatePhone($contactPrincipal, false)) {
            $this->errors[] = "Contact principal invalide (9 chiffres requis : 77/78/76/70/75) - Cellule B6 : '{$contactPrincipal}'";
        }
        
        // Validation contact secondaire (optionnel)
        if ($contactSecondaire && !$this->validatePhone($contactSecondaire, true)) {
            $this->warnings[] = "Contact secondaire invalide (ignoré) - Cellule B7";
            $contactSecondaire = null;
        }
        
        // Validation email (optionnel)
        if ($email && !$this->validateEmail($email)) {
            $this->warnings[] = "Email invalide (ignoré) - Cellule B8";
            $email = null;
        }
        
        $data = [
            'directeur_nom' => $nom,
            'directeur_contact_1' => $contactPrincipal,
            'directeur_contact_2' => $contactSecondaire,
            'directeur_email' => $email,
            'distance_siege' => $distance,
        ];
        
        $rapport->infoDirecteur()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );
    }
    
    private function importInfrastructures($rapport, $sheet)
    {
        // Lignes des infrastructures
        // Ligne 3: Header section 1.1
        // Lignes 5-9: Info directeur
        // Ligne 11: Header section 1.2
        // Ligne 13: Header tableau
        // Ligne 14: CPE (première infrastructure)
        $startRow = 14; // CPE commence à la ligne 14
        
        $data = [
            'cpe_existe' => $this->getOuiNon($sheet, 'B', $startRow),
            'cpe_nombre' => $this->getCellValue($sheet, 'C', $startRow, 0), // Nombre, default 0
            
            'cloture_existe' => $this->getOuiNon($sheet, 'B', $startRow + 1),
            'cloture_type' => $this->getCellValue($sheet, 'C', $startRow + 1), // Texte
            
            'eau_existe' => $this->getOuiNon($sheet, 'B', $startRow + 2),
            'eau_type' => $this->getCellValue($sheet, 'C', $startRow + 2), // Texte
            
            'electricite_existe' => $this->getOuiNon($sheet, 'B', $startRow + 3),
            'electricite_type' => $this->getCellValue($sheet, 'C', $startRow + 3), // Texte
            
            'connexion_internet_existe' => $this->getOuiNon($sheet, 'B', $startRow + 4),
            'connexion_internet_type' => $this->getCellValue($sheet, 'C', $startRow + 4), // Texte
            
            'cantine_existe' => $this->getOuiNon($sheet, 'B', $startRow + 5),
            'cantine_type' => $this->getCellValue($sheet, 'C', $startRow + 5), // Texte
        ];
        
        $rapport->infrastructuresBase()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );
    }
    
    private function importStructuresCommunautaires($rapport, $sheet)
    {
        // Section 1.3: Ligne 21 (Header) + 2 = Ligne 23 (Header tableau) + 1 = Ligne 24 (CGE)
        $startRow = 24; // CGE commence à la ligne 24
        
        // CGE
        $cgeRow = $startRow;
        $data = [
            'cge_existe' => $this->getOuiNon($sheet, 'B', $cgeRow),
            'cge_hommes' => $this->getCellValue($sheet, 'C', $cgeRow, 0),
            'cge_femmes' => $this->getCellValue($sheet, 'D', $cgeRow, 0),
            'cge_president_nom' => $this->getCellValue($sheet, 'E', $cgeRow),
            'cge_president_contact' => $this->getCellValue($sheet, 'F', $cgeRow),
            
            // Gouvernement Scolaire
            'gouvernement_existe' => $this->getOuiNon($sheet, 'B', $cgeRow + 1),
            'gouvernement_garcons' => $this->getCellValue($sheet, 'C', $cgeRow + 1, 0),
            'gouvernement_filles' => $this->getCellValue($sheet, 'D', $cgeRow + 1, 0),
            'gouvernement_president_nom' => $this->getCellValue($sheet, 'E', $cgeRow + 1),
            'gouvernement_president_contact' => $this->getCellValue($sheet, 'F', $cgeRow + 1),
            
            // APE
            'ape_existe' => $this->getOuiNon($sheet, 'B', $cgeRow + 2),
            'ape_hommes' => $this->getCellValue($sheet, 'C', $cgeRow + 2, 0),
            'ape_femmes' => $this->getCellValue($sheet, 'D', $cgeRow + 2, 0),
            'ape_president_nom' => $this->getCellValue($sheet, 'E', $cgeRow + 2),
            'ape_president_contact' => $this->getCellValue($sheet, 'F', $cgeRow + 2),
            
            // AME
            'ame_existe' => $this->getOuiNon($sheet, 'B', $cgeRow + 3),
            'ame_hommes' => $this->getCellValue($sheet, 'C', $cgeRow + 3, 0),
            'ame_femmes' => $this->getCellValue($sheet, 'D', $cgeRow + 3, 0),
            'ame_president_nom' => $this->getCellValue($sheet, 'E', $cgeRow + 3),
            'ame_president_contact' => $this->getCellValue($sheet, 'F', $cgeRow + 3),
        ];
        
        $rapport->structuresCommunautaires()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );
    }
    
    private function importLanguesProjets($rapport, $sheet)
    {
        // Section 1.4: Ligne 29 (Header) + 2 = Ligne 31
        $startRow = 31; // Langues et Projets
        
        $data = [
            'langue_nationale' => $this->getCellValue($sheet, 'B', $startRow),
            // 'enseignement_arabe' => $this->getOuiNon($sheet, 'B', $startRow + 1), // Pas dans BDD
            'projets_informatiques_existe' => $this->getOuiNon($sheet, 'B', $startRow + 2),
            'projets_informatiques_nom' => $this->getCellValue($sheet, 'B', $startRow + 3),
        ];
        
        $rapport->languesProjets()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );
    }
    
    private function importRessourcesFinancieres($rapport, $sheet)
    {
        // Section 1.5: Ligne 36 (Header) + 2 = Ligne 38 (Header tableau) + 1 = Ligne 39
        $startRow = 39; // Ressources Financières
        
        $data = [
            'subvention_etat_existe' => $this->getOuiNon($sheet, 'B', $startRow),
            'subvention_etat_montant' => $this->getCellValue($sheet, 'C', $startRow, 0),
            
            'subvention_partenaires_existe' => $this->getOuiNon($sheet, 'B', $startRow + 1),
            'subvention_partenaires_montant' => $this->getCellValue($sheet, 'C', $startRow + 1, 0),
            
            'subvention_collectivites_existe' => $this->getOuiNon($sheet, 'B', $startRow + 2),
            'subvention_collectivites_montant' => $this->getCellValue($sheet, 'C', $startRow + 2, 0),
            
            'subvention_communaute_existe' => $this->getOuiNon($sheet, 'B', $startRow + 3),
            'subvention_communaute_montant' => $this->getCellValue($sheet, 'C', $startRow + 3, 0),
            
            'ressources_generees_existe' => $this->getOuiNon($sheet, 'B', $startRow + 4),
            'ressources_generees_montant' => $this->getCellValue($sheet, 'C', $startRow + 4, 0),
        ];
        
        $rapport->ressourcesFinancieres()->updateOrCreate(
            ['rapport_id' => $rapport->id],
            $data
        );
    }
    
    /**
     * ÉTAPE 2 : Effectifs
     */
    private function importEtape2($rapport)
    {
        $sheet = $this->spreadsheet->getSheetByName('Etape 2 - Effectifs');
        if (!$sheet) {
            $this->errors[] = "Onglet 'Etape 2 - Effectifs' introuvable";
            return;
        }
        
        try {
            $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
            
            // 2.1 - Effectifs Totaux & Nombre de Classes
            $startRow = 6; // Première ligne de données
            foreach ($niveaux as $index => $niveau) {
                $row = $startRow + $index;
                
                $data = [
                    'rapport_id' => $rapport->id,
                    'niveau' => $niveau,
                    'nombre_classes' => $this->getCellValue($sheet, 'E', $row, 0),
                    'effectif_garcons' => $this->getCellValue($sheet, 'B', $row, 0),
                    'effectif_filles' => $this->getCellValue($sheet, 'C', $row, 0),
                    
                    // Redoublants (Section 2.2 - ligne 18 pour CI)
                    'redoublants_garcons' => $this->getCellValue($sheet, 'B', $row + 12, 0),
                    'redoublants_filles' => $this->getCellValue($sheet, 'C', $row + 12, 0),
                    
                    // Abandons (Section 2.3 - ligne 28 pour CI)
                    'abandons_garcons' => $this->getCellValue($sheet, 'B', $row + 22, 0),
                    'abandons_filles' => $this->getCellValue($sheet, 'C', $row + 22, 0),
                    
                    // Handicaps (Section 2.4 - ligne 38 pour CI)
                    'handicap_moteur_garcons' => $this->getCellValue($sheet, 'B', $row + 32, 0),
                    'handicap_moteur_filles' => $this->getCellValue($sheet, 'C', $row + 32, 0),
                    'handicap_visuel_garcons' => $this->getCellValue($sheet, 'D', $row + 32, 0),
                    'handicap_visuel_filles' => $this->getCellValue($sheet, 'E', $row + 32, 0),
                    'handicap_sourd_muet_garcons' => $this->getCellValue($sheet, 'F', $row + 32, 0),
                    'handicap_sourd_muet_filles' => $this->getCellValue($sheet, 'G', $row + 32, 0),
                    'handicap_deficience_intel_garcons' => $this->getCellValue($sheet, 'H', $row + 32, 0),
                    'handicap_deficience_intel_filles' => $this->getCellValue($sheet, 'I', $row + 32, 0),
                    
                    // Situations spéciales (Section 2.5 - ligne 48 pour CI)
                    'orphelins_garcons' => $this->getCellValue($sheet, 'B', $row + 42, 0),
                    'orphelins_filles' => $this->getCellValue($sheet, 'C', $row + 42, 0),
                    'sans_extrait_garcons' => $this->getCellValue($sheet, 'D', $row + 42, 0),
                    'sans_extrait_filles' => $this->getCellValue($sheet, 'E', $row + 42, 0),
                ];
                
                // Validation : filles <= total
                $total = $data['effectif_garcons'] + $data['effectif_filles'];
                if ($data['effectif_filles'] > $total) {
                    $this->warnings[] = "Effectifs {$niveau} : Filles ({$data['effectif_filles']}) > Total ({$total})";
                }
                
                $rapport->effectifs()->updateOrCreate(
                    ['rapport_id' => $rapport->id, 'niveau' => $niveau],
                    $data
                );
            }
            
            Log::info('Étape 2 (Effectifs) importée avec succès');
        } catch (Exception $e) {
            $this->errors[] = "Étape 2 : " . $e->getMessage();
        }
    }
    
    /**
     * ÉTAPE 3 : Examens et Recrutement
     */
    private function importEtape3($rapport)
    {
        $sheet = $this->spreadsheet->getSheetByName('Etape 3 - Examens');
        if (!$sheet) {
            $this->errors[] = "Onglet 'Etape 3 - Examens' introuvable";
            return;
        }
        
        try {
            // 3.1 - CMG (lignes 5-9)
            $cmgData = [
                'participants' => $this->getCellValue($sheet, 'B', 5, 0),
                'combinaison_1' => $this->getCellValue($sheet, 'B', 6),
                'combinaison_2' => $this->getCellValue($sheet, 'B', 7),
                'combinaison_3' => $this->getCellValue($sheet, 'B', 8),
                'autres_combinaisons' => $this->getCellValue($sheet, 'B', 9),
            ];
            $rapport->cmg()->updateOrCreate(['rapport_id' => $rapport->id], $cmgData);
            
            // 3.2 - CFEE (lignes 14-15)
            $cfeeData = [
                'candidats_total' => $this->getCellValue($sheet, 'B', 14, 0),
                'candidats_filles' => $this->getCellValue($sheet, 'C', 14, 0),
                'admis_total' => $this->getCellValue($sheet, 'B', 15, 0),
                'admis_filles' => $this->getCellValue($sheet, 'C', 15, 0),
            ];
            
            // Validation : admis <= candidats
            if ($cfeeData['admis_total'] > $cfeeData['candidats_total']) {
                $this->warnings[] = "CFEE : Admis total ({$cfeeData['admis_total']}) > Candidats ({$cfeeData['candidats_total']})";
            }
            if ($cfeeData['admis_filles'] > $cfeeData['candidats_filles']) {
                $this->warnings[] = "CFEE : Admis filles ({$cfeeData['admis_filles']}) > Candidats filles ({$cfeeData['candidats_filles']})";
            }
            
            $rapport->cfee()->updateOrCreate(['rapport_id' => $rapport->id], $cfeeData);
            
            // 3.3 - Entrée Sixième (lignes 20-21)
            $sixiemeData = [
                'candidats_total' => $this->getCellValue($sheet, 'B', 20, 0),
                'candidats_filles' => $this->getCellValue($sheet, 'C', 20, 0),
                'admis_total' => $this->getCellValue($sheet, 'B', 21, 0),
                'admis_filles' => $this->getCellValue($sheet, 'C', 21, 0),
            ];
            $rapport->entreeSixieme()->updateOrCreate(['rapport_id' => $rapport->id], $sixiemeData);
            
            // 3.4 - Recrutement CI (lignes 26-27)
            $ciData = [
                'recrutement_octobre_garcons' => $this->getCellValue($sheet, 'B', 26, 0),
                'recrutement_octobre_filles' => $this->getCellValue($sheet, 'C', 26, 0),
                'recrutement_mai_garcons' => $this->getCellValue($sheet, 'B', 27, 0),
                'recrutement_mai_filles' => $this->getCellValue($sheet, 'C', 27, 0),
            ];
            $rapport->recrutementCi()->updateOrCreate(['rapport_id' => $rapport->id], $ciData);
            
            Log::info('Étape 3 (Examens) importée avec succès');
        } catch (Exception $e) {
            $this->errors[] = "Étape 3 : " . $e->getMessage();
        }
    }
    
    /**
     * ÉTAPE 4 : Personnel Enseignant
     */
    private function importEtape4($rapport)
    {
        $sheet = $this->spreadsheet->getSheetByName('Etape 4 - Personnel');
        if (!$sheet) {
            $this->errors[] = "Onglet 'Etape 4 - Personnel' introuvable";
            return;
        }
        
        try {
            $startRow = 6; // Répartition par spécialité
            
            $data = [
                // 4.1 - Répartition par Spécialité
                'titulaires_hommes' => $this->getCellValue($sheet, 'B', $startRow, 0),
                'titulaires_femmes' => $this->getCellValue($sheet, 'C', $startRow, 0),
                'vacataires_hommes' => $this->getCellValue($sheet, 'B', $startRow + 1, 0),
                'vacataires_femmes' => $this->getCellValue($sheet, 'C', $startRow + 1, 0),
                'volontaires_hommes' => $this->getCellValue($sheet, 'B', $startRow + 2, 0),
                'volontaires_femmes' => $this->getCellValue($sheet, 'C', $startRow + 2, 0),
                'contractuels_hommes' => $this->getCellValue($sheet, 'B', $startRow + 3, 0),
                'contractuels_femmes' => $this->getCellValue($sheet, 'C', $startRow + 3, 0),
                'communautaires_hommes' => $this->getCellValue($sheet, 'B', $startRow + 4, 0),
                'communautaires_femmes' => $this->getCellValue($sheet, 'C', $startRow + 4, 0),
                
                // 4.2 - Répartition par Corps (ligne 16)
                'instituteurs_hommes' => $this->getCellValue($sheet, 'B', $startRow + 10, 0),
                'instituteurs_femmes' => $this->getCellValue($sheet, 'C', $startRow + 10, 0),
                'instituteurs_adjoints_hommes' => $this->getCellValue($sheet, 'B', $startRow + 11, 0),
                'instituteurs_adjoints_femmes' => $this->getCellValue($sheet, 'C', $startRow + 11, 0),
                'professeurs_hommes' => $this->getCellValue($sheet, 'B', $startRow + 12, 0),
                'professeurs_femmes' => $this->getCellValue($sheet, 'C', $startRow + 12, 0),
                
                // 4.3 - Répartition par Diplômes
                'bac_hommes' => $this->getCellValue($sheet, 'B', $startRow + 16, 0),
                'bac_femmes' => $this->getCellValue($sheet, 'C', $startRow + 16, 0),
                'bfem_hommes' => $this->getCellValue($sheet, 'B', $startRow + 17, 0),
                'bfem_femmes' => $this->getCellValue($sheet, 'C', $startRow + 17, 0),
                'cfee_hommes' => $this->getCellValue($sheet, 'B', $startRow + 18, 0),
                'cfee_femmes' => $this->getCellValue($sheet, 'C', $startRow + 18, 0),
                'licence_hommes' => $this->getCellValue($sheet, 'B', $startRow + 19, 0),
                'licence_femmes' => $this->getCellValue($sheet, 'C', $startRow + 19, 0),
                'master_hommes' => $this->getCellValue($sheet, 'B', $startRow + 20, 0),
                'master_femmes' => $this->getCellValue($sheet, 'C', $startRow + 20, 0),
                'autres_diplomes_hommes' => $this->getCellValue($sheet, 'B', $startRow + 21, 0),
                'autres_diplomes_femmes' => $this->getCellValue($sheet, 'C', 27, 0),
                
                // 4.4 - Compétences TIC (ligne 31)
                'enseignants_formes_tic_hommes' => $this->getCellValue($sheet, 'B', 31, 0),
                'enseignants_formes_tic_femmes' => $this->getCellValue($sheet, 'C', 31, 0),
            ];
            
            $rapport->personnelEnseignant()->updateOrCreate(['rapport_id' => $rapport->id], $data);
            
            Log::info('Étape 4 (Personnel) importée avec succès');
        } catch (Exception $e) {
            $this->errors[] = "Étape 4 : " . $e->getMessage();
        }
    }
    
    /**
     * ÉTAPE 5 : Matériel Pédagogique
     */
    private function importEtape5($rapport)
    {
        $sheet = $this->spreadsheet->getSheetByName('Etape 5 - Materiel');
        if (!$sheet) {
            $this->errors[] = "Onglet 'Etape 5 - Materiel' introuvable";
            return;
        }
        
        try {
            // 5.1 - Manuels Élèves (ligne 6 = première matière)
            // Structure: 1 record PAR NIVEAU (6 records au total)
            $startRow = 6;
            $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
            $colonnesNiveaux = ['B', 'C', 'D', 'E', 'F', 'G'];
            
            // Supprimer les anciens records pour ce rapport
            $rapport->manuelsEleves()->delete();
            
            // Créer 6 records (un par niveau)
            foreach ($niveaux as $index => $niveau) {
                $col = $colonnesNiveaux[$index];
                
                $manuelsData = [
                    'rapport_id' => $rapport->id,
                    'niveau' => $niveau,
                    'lc_francais' => $this->getCellValue($sheet, $col, $startRow, 0),
                    'mathematiques' => $this->getCellValue($sheet, $col, $startRow + 1, 0),
                    'edd' => $this->getCellValue($sheet, $col, $startRow + 2, 0),
                    'dm' => $this->getCellValue($sheet, $col, $startRow + 3, 0),
                    'manuel_classe' => $this->getCellValue($sheet, $col, $startRow + 4, 0),
                    'livret_maison' => $this->getCellValue($sheet, $col, $startRow + 5, 0),
                    'livret_devoir_gradue' => $this->getCellValue($sheet, $col, $startRow + 6, 0),
                    'planche_alphabetique' => $this->getCellValue($sheet, $col, $startRow + 7, 0),
                    'manuel_arabe' => $this->getCellValue($sheet, $col, $startRow + 8, 0),
                    'manuel_religion' => $this->getCellValue($sheet, $col, $startRow + 9, 0),
                    'autre_religion' => $this->getCellValue($sheet, $col, $startRow + 10, 0),
                    'autres_manuels' => $this->getCellValue($sheet, $col, $startRow + 11, 0),
                ];
                
                $rapport->manuelsEleves()->create($manuelsData);
            }
            
            // 5.2 - Guides du Maître (ligne 21 = premier guide)
            // Structure: 1 record PAR NIVEAU (6 records au total)
            $startRow = 21;
            
            // Supprimer les anciens records pour ce rapport
            $rapport->manuelsMaitre()->delete();
            
            // Créer 6 records (un par niveau)
            foreach ($niveaux as $index => $niveau) {
                $col = $colonnesNiveaux[$index];
                
                $guidesData = [
                    'rapport_id' => $rapport->id,
                    'niveau' => $niveau,
                    'guide_lc_francais' => $this->getCellValue($sheet, $col, $startRow, 0),
                    'guide_mathematiques' => $this->getCellValue($sheet, $col, $startRow + 1, 0),
                    'guide_edd' => $this->getCellValue($sheet, $col, $startRow + 2, 0),
                    'guide_dm' => $this->getCellValue($sheet, $col, $startRow + 3, 0),
                    'guide_pedagogique' => $this->getCellValue($sheet, $col, $startRow + 4, 0),
                    'guide_arabe_religieux' => $this->getCellValue($sheet, $col, $startRow + 5, 0),
                    'guide_langue_nationale' => $this->getCellValue($sheet, $col, $startRow + 6, 0),
                    'cahier_recits' => $this->getCellValue($sheet, $col, $startRow + 7, 0),
                    'autres_manuels_maitre' => $this->getCellValue($sheet, $col, $startRow + 8, 0),
                ];
                
                $rapport->manuelsMaitre()->create($guidesData);
            }
            
            // 5.3 - Dictionnaires (ligne 33 = Français)
            $startRow = 33;
            $dicoData = [
                'dico_francais_total' => $this->getCellValue($sheet, 'B', $startRow, 0),
                'dico_francais_bon_etat' => $this->getCellValue($sheet, 'C', $startRow, 0),
                'dico_arabe_total' => $this->getCellValue($sheet, 'B', $startRow + 1, 0),
                'dico_arabe_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 1, 0),
                'dico_autre_total' => $this->getCellValue($sheet, 'B', $startRow + 2, 0),
                'dico_autre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 2, 0),
                // Champs supplémentaires (optionnels, seront NULL si non remplis)
                'besoins_dictionnaires' => null,
                'budget_estime_dictionnaires' => null,
                'observations_dictionnaires' => null,
            ];
            
            // Validation : bon_etat <= total
            foreach (['francais', 'arabe', 'autre'] as $type) {
                $key = 'dico_' . $type;
                if ($dicoData[$key . '_bon_etat'] > $dicoData[$key . '_total']) {
                    $this->warnings[] = "Dictionnaires {$type} : Bon état > Total";
                }
            }
            
            $rapport->dictionnaires()->updateOrCreate(['rapport_id' => $rapport->id], $dicoData);
            
            // 5.4 - Matériel Didactique (ligne 39 = Dictionnaires Français)
            $startRow = 39;
            
            $materielData = [
                // Dictionnaires (déjà dans section 5.3, mais conservés ici pour compatibilité BDD)
                'dico_francais_total' => $this->getCellValue($sheet, 'B', $startRow, 0),
                'dico_francais_bon_etat' => $this->getCellValue($sheet, 'C', $startRow, 0),
                'dico_arabe_total' => $this->getCellValue($sheet, 'B', $startRow + 1, 0),
                'dico_arabe_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 1, 0),
                'dico_autre_total' => $this->getCellValue($sheet, 'B', $startRow + 2, 0),
                'dico_autre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 2, 0),
                
                // Matériel didactique proprement dit
                'regle_plate_total' => $this->getCellValue($sheet, 'B', $startRow + 3, 0),
                'regle_plate_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 3, 0),
                'equerre_total' => $this->getCellValue($sheet, 'B', $startRow + 4, 0),
                'equerre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 4, 0),
                'compas_total' => $this->getCellValue($sheet, 'B', $startRow + 5, 0),
                'compas_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 5, 0),
                'rapporteur_total' => $this->getCellValue($sheet, 'B', $startRow + 6, 0),
                'rapporteur_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 6, 0),
                'decametre_total' => $this->getCellValue($sheet, 'B', $startRow + 7, 0),
                'decametre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 7, 0),
                'chaine_arpenteur_total' => $this->getCellValue($sheet, 'B', $startRow + 8, 0),
                'chaine_arpenteur_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 8, 0),
                'boussole_total' => $this->getCellValue($sheet, 'B', $startRow + 9, 0),
                'boussole_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 9, 0),
                'thermometre_total' => $this->getCellValue($sheet, 'B', $startRow + 10, 0),
                'thermometre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 10, 0),
                'kit_capacite_total' => $this->getCellValue($sheet, 'B', $startRow + 11, 0),
                'kit_capacite_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 11, 0),
                'balance_total' => $this->getCellValue($sheet, 'B', $startRow + 12, 0),
                'balance_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 12, 0),
                'globe_total' => $this->getCellValue($sheet, 'B', $startRow + 13, 0),
                'globe_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 13, 0),
                'cartes_murales_total' => $this->getCellValue($sheet, 'B', $startRow + 14, 0),
                'cartes_murales_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 14, 0),
                'planches_illustrees_total' => $this->getCellValue($sheet, 'B', $startRow + 15, 0),
                'planches_illustrees_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 15, 0),
                'kit_materiel_scientifique_total' => $this->getCellValue($sheet, 'B', $startRow + 16, 0),
                'kit_materiel_scientifique_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 16, 0),
                'autres_materiel_total' => $this->getCellValue($sheet, 'B', $startRow + 17, 0),
                'autres_materiel_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 17, 0),
            ];
            $rapport->materielDidactique()->updateOrCreate(['rapport_id' => $rapport->id], $materielData);
            
            Log::info('Étape 5 (Matériel) importée avec succès');
        } catch (Exception $e) {
            $this->errors[] = "Étape 5 : " . $e->getMessage();
        }
    }
    
    /**
     * ÉTAPE 6 : Infrastructure & Équipements
     */
    private function importEtape6($rapport)
    {
        $sheet = $this->spreadsheet->getSheetByName('Etape 6 - Infrastructure');
        if (!$sheet) {
            $this->errors[] = "Onglet 'Etape 6 - Infrastructure' introuvable";
            return;
        }
        
        try {
            // 6.1 - Capital Immobilier (ligne 6 = Salles en dur)
            $startRow = 6;
            
            $immoData = [
                'salles_dur_total' => $this->getCellValue($sheet, 'B', $startRow, 0),
                'salles_dur_bon_etat' => $this->getCellValue($sheet, 'C', $startRow, 0),
                'abris_provisoires_total' => $this->getCellValue($sheet, 'B', $startRow + 1, 0),
                'abris_provisoires_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 1, 0),
                'bloc_admin_total' => $this->getCellValue($sheet, 'B', $startRow + 2, 0),
                'bloc_admin_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 2, 0),
                'magasin_total' => $this->getCellValue($sheet, 'B', $startRow + 3, 0),
                'magasin_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 3, 0),
                'salle_informatique_total' => $this->getCellValue($sheet, 'B', $startRow + 4, 0),
                'salle_informatique_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 4, 0),
                'salle_bibliotheque_total' => $this->getCellValue($sheet, 'B', $startRow + 5, 0),
                'salle_bibliotheque_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 5, 0),
                'cuisine_total' => $this->getCellValue($sheet, 'B', $startRow + 6, 0),
                'cuisine_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 6, 0),
                'refectoire_total' => $this->getCellValue($sheet, 'B', $startRow + 7, 0),
                'refectoire_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 7, 0),
                'toilettes_enseignants_total' => $this->getCellValue($sheet, 'B', $startRow + 8, 0),
                'toilettes_enseignants_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 8, 0),
                'toilettes_garcons_total' => $this->getCellValue($sheet, 'B', $startRow + 9, 0),
                'toilettes_garcons_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 9, 0),
                'toilettes_filles_total' => $this->getCellValue($sheet, 'B', $startRow + 10, 0),
                'toilettes_filles_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 10, 0),
                'toilettes_mixtes_total' => $this->getCellValue($sheet, 'B', $startRow + 11, 0),
                'toilettes_mixtes_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 11, 0),
                'logement_directeur_total' => $this->getCellValue($sheet, 'B', $startRow + 12, 0),
                'logement_directeur_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 12, 0),
                'logement_gardien_total' => $this->getCellValue($sheet, 'B', $startRow + 13, 0),
                'logement_gardien_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 13, 0),
                'autres_infrastructures_total' => $this->getCellValue($sheet, 'B', $startRow + 14, 0),
                'autres_infrastructures_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 14, 0),
            ];
            $rapport->capitalImmobilier()->updateOrCreate(['rapport_id' => $rapport->id], $immoData);
            
            // 6.2 - Capital Mobilier (ligne 24 = Tables-bancs élèves)
            $startRow = 24;
            
            $mobData = [
                'tables_bancs_total' => $this->getCellValue($sheet, 'B', $startRow, 0),
                'tables_bancs_bon_etat' => $this->getCellValue($sheet, 'C', $startRow, 0),
                'chaises_eleves_total' => $this->getCellValue($sheet, 'B', $startRow + 1, 0),
                'chaises_eleves_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 1, 0),
                'tables_individuelles_total' => $this->getCellValue($sheet, 'B', $startRow + 2, 0),
                'tables_individuelles_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 2, 0),
                'bureaux_maitre_total' => $this->getCellValue($sheet, 'B', $startRow + 3, 0),
                'bureaux_maitre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 3, 0),
                'chaises_maitre_total' => $this->getCellValue($sheet, 'B', $startRow + 4, 0),
                'chaises_maitre_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 4, 0),
                'tableaux_total' => $this->getCellValue($sheet, 'B', $startRow + 5, 0),
                'tableaux_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 5, 0),
                'armoires_total' => $this->getCellValue($sheet, 'B', $startRow + 6, 0),
                'armoires_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 6, 0),
                'bureaux_admin_total' => $this->getCellValue($sheet, 'B', $startRow + 7, 0),
                'bureaux_admin_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 7, 0),
                'chaises_admin_total' => $this->getCellValue($sheet, 'B', $startRow + 8, 0),
                'chaises_admin_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 8, 0),
                'bureaux_total' => $this->getCellValue($sheet, 'B', $startRow + 9, 0),
                'bureaux_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 9, 0),
                'chaises_total' => $this->getCellValue($sheet, 'B', $startRow + 10, 0),
                'chaises_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 10, 0),
                'tableaux_mobiles_total' => $this->getCellValue($sheet, 'B', $startRow + 11, 0),
                'tableaux_mobiles_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 11, 0),
                'tableaux_interactifs_total' => $this->getCellValue($sheet, 'B', $startRow + 12, 0),
                'tableaux_interactifs_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 12, 0),
                'tableaux_muraux_total' => $this->getCellValue($sheet, 'B', $startRow + 13, 0),
                'tableaux_muraux_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 13, 0),
                'mat_drapeau_total' => $this->getCellValue($sheet, 'B', $startRow + 14, 0),
                'mat_drapeau_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 14, 0),
                'drapeau_total' => $this->getCellValue($sheet, 'B', $startRow + 15, 0),
                'drapeau_bon_etat' => $this->getCellValue($sheet, 'C', $startRow + 15, 0),
            ];
            $rapport->capitalMobilier()->updateOrCreate(['rapport_id' => $rapport->id], $mobData);
            
            // 6.3 - Équipements Informatiques (ligne 36 = Ordinateurs)
            $startRow = 36;
            $equipements = [
                'ordinateurs', 'ordinateurs_portables', 'tablettes', 'imprimantes', 'photocopieuses',
                'projecteurs', 'scanners', 'onduleurs', 'autres_equipements'
            ];
            
            $equipData = [];
            foreach ($equipements as $index => $equip) {
                $row = $startRow + $index;
                $equipData[$equip . '_total'] = $this->getCellValue($sheet, 'B', $row, 0);
                $equipData[$equip . '_bon_etat'] = $this->getCellValue($sheet, 'C', $row, 0);
            }
            $rapport->equipementInformatique()->updateOrCreate(['rapport_id' => $rapport->id], $equipData);
            
            Log::info('Étape 6 (Infrastructure) importée avec succès');
        } catch (Exception $e) {
            $this->errors[] = "Étape 6 : " . $e->getMessage();
        }
    }
    
    // =================== UTILITAIRES ===================
    
    /**
     * Récupérer la valeur d'une cellule
     */
    private function getCellValue($sheet, $col, $row, $default = null)
    {
        try {
            $value = $sheet->getCell($col . $row)->getCalculatedValue();
            
            // Nettoyer la valeur
            if (is_string($value)) {
                $value = trim($value);
                if ($value === '' || $value === 'NULL' || strtolower($value) === 'null') {
                    return $default;
                }
            }
            
            // Si $default est un nombre (0), convertir la valeur en nombre
            if (is_numeric($default) && $value !== null) {
                return is_numeric($value) ? (float)$value : $default;
            }
            
            return $value ?? $default;
        } catch (Exception $e) {
            Log::warning("Erreur lecture cellule {$col}{$row}: " . $e->getMessage());
            return $default;
        }
    }
    
    /**
     * Convertir OUI/NON en booléen
     */
    private function getOuiNon($sheet, $col, $row)
    {
        $value = $this->getCellValue($sheet, $col, $row);
        return strtoupper($value) === 'OUI' ? 1 : 0;
    }
    
    /**
     * Valider un nom (min 3 car, 2 mots min, lettres uniquement)
     */
    private function validateNom($nom)
    {
        if (empty($nom) || strlen($nom) < 3) {
            return false;
        }
        
        // Au moins 2 mots (un espace)
        if (substr_count($nom, ' ') < 1) {
            return false;
        }
        
        // Pas de chiffres
        if (preg_match('/\d/', $nom)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Valider un téléphone (9 chiffres, commence par 77/78/76/70/75)
     */
    private function validatePhone($phone, $optional = false)
    {
        if (empty($phone)) {
            return $optional;
        }
        
        // Nettoyer (enlever espaces, tirets, etc.)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // 9 chiffres exactement
        if (strlen($phone) !== 9) {
            return false;
        }
        
        // Commence par 77, 78, 76, 70 ou 75
        $prefix = substr($phone, 0, 2);
        return in_array($prefix, ['77', '78', '76', '70', '75']);
    }
    
    /**
     * Valider un email
     */
    private function validateEmail($email)
    {
        if (empty($email)) {
            return true; // Optionnel
        }
        
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
