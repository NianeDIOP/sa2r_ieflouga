<?php

namespace App\Services;

use App\Models\Rapport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RapportExcelService
{
    protected $rapport;
    protected $spreadsheet;

    public function __construct(Rapport $rapport)
    {
        // Charger TOUTES les relations du rapport - EXACTEMENT comme dans show.blade.php
        $this->rapport = $rapport->load([
            'etablissement',
            'soumis_par',
            'valide_par',
            // Section 1 : Informations Générales et Financières
            'infoDirecteur',
            'infrastructuresBase',
            'structuresCommunautaires',
            'languesProjets',
            'ressourcesFinancieres',
            // Section 2 : Effectifs
            'effectifs',
            // Section 3 : Examens et Recrutement
            'cfee',
            'entreeSixieme',
            'cmg',
            'recrutementCi',
            // Section 4 : Personnel
            'personnelEnseignant',
            // Section 5 : Matériel Pédagogique
            'manuelsEleves',
            'manuelsMaitre',
            'dictionnaires',
            'materielDidactique',
            // Section 6 : Infrastructure & Équipements
            'capitalImmobilier',
            'capitalMobilier',
            'equipementInformatique',
        ]);

        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet->removeSheetByIndex(0);
    }

    /**
     * Générer le fichier Excel - ÉTAPE PAR ÉTAPE
     */
    public function generate()
    {
        $this->createSheet1_InfoGenerales();
        $this->createSheet2_Effectifs();
        $this->createSheet3_ExamensRecrutement();
        $this->createSheet4_PersonnelEnseignant();
        $this->createSheet5_MaterielPedagogique();
        $this->createSheet6_InfrastructureEquipements();
        
        return $this->spreadsheet;
    }

    /**
     * Télécharger le fichier
     */
    public function download($fileName = null)
    {
        $this->generate();
        
        if (!$fileName) {
            $fileName = 'Rapport_' . 
                        ($this->rapport->etablissement->code ?? 'SANS_CODE') . '_' . 
                        $this->rapport->annee_scolaire . '.xlsx';
        }

        $writer = new Xlsx($this->spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    /**
     * Utilitaires de style
     */
    protected function styleHeader($sheet, $row, $color = '10B981')
    {
        $sheet->getStyle("A{$row}:Z{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
    }

    protected function styleSubHeader($sheet, $row)
    {
        $sheet->getStyle("A{$row}:Z{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
    }

    protected function styleBold($sheet, $cell)
    {
        $sheet->getStyle($cell)->getFont()->setBold(true);
    }

    /**
     * ==================== FEUILLE 1 : INFORMATIONS GÉNÉRALES ====================
     * Basée sur la section "SECTION 1" de show.blade.php
     */
    protected function createSheet1_InfoGenerales()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('1. Infos Générales');
        
        $row = 1;
        
        // TITRE PRINCIPAL
        $sheet->setCellValue("A{$row}", 'RAPPORT DE RENTRÉE - ' . $this->rapport->annee_scolaire);
        $sheet->mergeCells("A{$row}:D{$row}");
        $this->styleHeader($sheet, $row, '10B981');
        
        $row += 2;
        
        // === INFORMATIONS ÉTABLISSEMENT ===
        $sheet->setCellValue("A{$row}", 'INFORMATIONS ÉTABLISSEMENT');
        $sheet->mergeCells("A{$row}:D{$row}");
        $this->styleBold($sheet, "A{$row}");
        $row++;
        
        $etab = $this->rapport->etablissement;
        $sheet->setCellValue("A{$row}", 'Établissement')->setCellValue("B{$row}", $etab->etablissement ?? '');
        $row++;
        $sheet->setCellValue("A{$row}", 'Code')->setCellValue("B{$row}", $etab->code ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Statut')->setCellValue("B{$row}", $etab->statut ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Type')->setCellValue("B{$row}", $etab->type_statut ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Arrondissement')->setCellValue("B{$row}", $etab->arrondissement ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Commune')->setCellValue("B{$row}", $etab->commune ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'District')->setCellValue("B{$row}", $etab->district ?? 'N/A');
        $row++;
        $sheet->setCellValue("A{$row}", 'Zone')->setCellValue("B{$row}", $etab->zone ?? 'N/A');
        $row++;
        
        // Statut du rapport
        $statutLabels = [
            'brouillon' => 'BROUILLON',
            'soumis' => 'SOUMIS',
            'valide' => 'VALIDÉ',
            'rejete' => 'REJETÉ'
        ];
        $sheet->setCellValue("A{$row}", 'Statut du Rapport')
              ->setCellValue("B{$row}", $statutLabels[$this->rapport->statut] ?? $this->rapport->statut);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Date de Soumission')
              ->setCellValue("B{$row}", $this->rapport->date_soumission ? $this->rapport->date_soumission->format('d/m/Y à H:i') : 'Non soumis');
        
        $row += 2;
        
        // === INFORMATIONS DIRECTEUR ===
        if ($this->rapport->infoDirecteur) {
            $dir = $this->rapport->infoDirecteur;
            
            $sheet->setCellValue("A{$row}", 'INFORMATIONS DU DIRECTEUR');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Nom du Directeur')->setCellValue("B{$row}", $dir->directeur_nom ?? 'N/A');
            $row++;
            $sheet->setCellValue("A{$row}", 'Contact Principal')->setCellValue("B{$row}", $dir->directeur_contact_1 ?? 'N/A');
            $row++;
            
            if ($dir->directeur_contact_2) {
                $sheet->setCellValue("A{$row}", 'Contact Secondaire')->setCellValue("B{$row}", $dir->directeur_contact_2);
                $row++;
            }
            
            $sheet->setCellValue("A{$row}", 'Email')->setCellValue("B{$row}", $dir->directeur_email ?? 'N/A');
            $row++;
            
            if ($dir->distance_siege) {
                $sheet->setCellValue("A{$row}", 'Distance au Siège IEF (km)')
                      ->setCellValue("B{$row}", number_format($dir->distance_siege, 2));
                $row++;
            }
            
            $row += 2;
        }
        
        // === INFRASTRUCTURES DE BASE ===
        if ($this->rapport->infrastructuresBase) {
            $infra = $this->rapport->infrastructuresBase;
            
            $sheet->setCellValue("A{$row}", 'INFRASTRUCTURES DE BASE');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Infrastructure')
                  ->setCellValue("B{$row}", 'Existence')
                  ->setCellValue("C{$row}", 'Type / Détails');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'CPE (Centre Préscolaire)')
                  ->setCellValue("B{$row}", $infra->cpe_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $infra->cpe_existe && $infra->cpe_nombre ? $infra->cpe_nombre . ' centre(s)' : '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Clôture')
                  ->setCellValue("B{$row}", $infra->cloture_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $infra->cloture_type ?? '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Point d\'Eau')
                  ->setCellValue("B{$row}", $infra->eau_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $infra->eau_type ?? '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Électricité')
                  ->setCellValue("B{$row}", $infra->electricite_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $infra->electricite_type ?? '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Connexion Internet')
                  ->setCellValue("B{$row}", $infra->connexion_internet_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $infra->connexion_internet_type ?? '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Cantine Scolaire')
                  ->setCellValue("B{$row}", $infra->cantine_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $infra->cantine_type ?? '-');
            
            $row += 2;
        }
        
        // === RESSOURCES FINANCIÈRES ===
        if ($this->rapport->ressourcesFinancieres) {
            $fin = $this->rapport->ressourcesFinancieres;
            
            $sheet->setCellValue("A{$row}", 'RESSOURCES FINANCIÈRES');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Source de Financement')
                  ->setCellValue("B{$row}", 'Existe')
                  ->setCellValue("C{$row}", 'Montant (FCFA)');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Subvention de l\'État')
                  ->setCellValue("B{$row}", $fin->subvention_etat_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $fin->subvention_etat_existe ? number_format($fin->subvention_etat_montant ?? 0, 0, ',', ' ') : '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Subvention des Partenaires')
                  ->setCellValue("B{$row}", $fin->subvention_partenaires_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $fin->subvention_partenaires_existe ? number_format($fin->subvention_partenaires_montant ?? 0, 0, ',', ' ') : '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Subvention des Collectivités Locales')
                  ->setCellValue("B{$row}", $fin->subvention_collectivites_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $fin->subvention_collectivites_existe ? number_format($fin->subvention_collectivites_montant ?? 0, 0, ',', ' ') : '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Contribution Communauté (CGE/APE)')
                  ->setCellValue("B{$row}", $fin->subvention_communaute_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $fin->subvention_communaute_existe ? number_format($fin->subvention_communaute_montant ?? 0, 0, ',', ' ') : '-');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Ressources Générées (AGR)')
                  ->setCellValue("B{$row}", $fin->ressources_generees_existe ? 'Oui' : 'Non')
                  ->setCellValue("C{$row}", $fin->ressources_generees_existe ? number_format($fin->ressources_generees_montant ?? 0, 0, ',', ' ') : '-');
            $row++;
            
            // Total en gras
            $sheet->setCellValue("A{$row}", 'TOTAL DES RESSOURCES')
                  ->setCellValue("C{$row}", number_format($fin->total_ressources ?? 0, 0, ',', ' '));
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "C{$row}");
            
            $row += 2;
        }
        
        // === STRUCTURES COMMUNAUTAIRES ===
        if ($this->rapport->structuresCommunautaires) {
            $struct = $this->rapport->structuresCommunautaires;
            
            $sheet->setCellValue("A{$row}", 'STRUCTURES COMMUNAUTAIRES');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            // CGE
            if ($struct->cge_existe) {
                $sheet->setCellValue("A{$row}", 'Comité de Gestion de l\'Établissement (CGE)');
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                $sheet->setCellValue("A{$row}", 'Hommes')->setCellValue("B{$row}", $struct->cge_hommes ?? 0);
                $row++;
                $sheet->setCellValue("A{$row}", 'Femmes')->setCellValue("B{$row}", $struct->cge_femmes ?? 0);
                $row++;
                $sheet->setCellValue("A{$row}", 'Total Membres')->setCellValue("B{$row}", $struct->cge_total ?? 0);
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                if ($struct->cge_president_nom) {
                    $sheet->setCellValue("A{$row}", 'Président(e)')
                          ->setCellValue("B{$row}", $struct->cge_president_nom . ($struct->cge_president_contact ? ' - ' . $struct->cge_president_contact : ''));
                    $row++;
                }
                
                if ($struct->cge_tresorier_nom) {
                    $sheet->setCellValue("A{$row}", 'Trésorier(ère)')
                          ->setCellValue("B{$row}", $struct->cge_tresorier_nom . ($struct->cge_tresorier_contact ? ' - ' . $struct->cge_tresorier_contact : ''));
                    $row++;
                }
                
                $row++;
            }
            
            // G.Scol
            if ($struct->gscol_existe) {
                $sheet->setCellValue("A{$row}", 'Gouvernement Scolaire (G.Scol)');
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                $sheet->setCellValue("A{$row}", 'Garçons')->setCellValue("B{$row}", $struct->gscol_garcons ?? 0);
                $row++;
                $sheet->setCellValue("A{$row}", 'Filles')->setCellValue("B{$row}", $struct->gscol_filles ?? 0);
                $row++;
                $sheet->setCellValue("A{$row}", 'Total Membres')->setCellValue("B{$row}", $struct->gscol_total ?? 0);
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                if ($struct->gscol_president_nom) {
                    $sheet->setCellValue("A{$row}", 'Président(e)')
                          ->setCellValue("B{$row}", $struct->gscol_president_nom . ($struct->gscol_president_contact ? ' - ' . $struct->gscol_president_contact : ''));
                    $row++;
                }
                
                $row++;
            }
            
            // APE
            if ($struct->ape_existe) {
                $sheet->setCellValue("A{$row}", 'Association des Parents d\'Élèves (APE)');
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                $sheet->setCellValue("A{$row}", 'Hommes')->setCellValue("B{$row}", $struct->ape_hommes ?? 0);
                $row++;
                $sheet->setCellValue("A{$row}", 'Femmes')->setCellValue("B{$row}", $struct->ape_femmes ?? 0);
                $row++;
                $sheet->setCellValue("A{$row}", 'Total Membres')->setCellValue("B{$row}", $struct->ape_total ?? 0);
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                if ($struct->ape_president_nom) {
                    $sheet->setCellValue("A{$row}", 'Président(e)')
                          ->setCellValue("B{$row}", $struct->ape_president_nom . ($struct->ape_president_contact ? ' - ' . $struct->ape_president_contact : ''));
                    $row++;
                }
                
                $row++;
            }
            
            // AME
            if ($struct->ame_existe) {
                $sheet->setCellValue("A{$row}", 'Association des Mères d\'Élèves (AME)');
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                $sheet->setCellValue("A{$row}", 'Nombre de Membres')->setCellValue("B{$row}", $struct->ame_nombre ?? 0);
                $row++;
                
                if ($struct->ame_president_nom) {
                    $sheet->setCellValue("A{$row}", 'Présidente')
                          ->setCellValue("B{$row}", $struct->ame_president_nom . ($struct->ame_president_contact ? ' - ' . $struct->ame_president_contact : ''));
                    $row++;
                }
            }
        }
        
        // === LANGUES & PROJETS ===
        if ($this->rapport->languesProjets) {
            $lang = $this->rapport->languesProjets;
            
            $row += 2;
            $sheet->setCellValue("A{$row}", 'LANGUES NATIONALES & PROJETS INFORMATIQUES');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Langue Nationale Enseignée')
                  ->setCellValue("B{$row}", $lang->langue_nationale ? ucfirst($lang->langue_nationale) : 'Aucune');
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Projets Informatiques')
                  ->setCellValue("B{$row}", $lang->projets_informatiques_existe ? 'Oui' : 'Non');
            $row++;
            
            if ($lang->projets_informatiques_existe && $lang->projets_informatiques_nom) {
                $sheet->setCellValue("A{$row}", 'Nom des Projets')
                      ->setCellValue("B{$row}", $lang->projets_informatiques_nom);
            }
        }
        
        // Ajuster les colonnes
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
    }

    /**
     * ==================== FEUILLE 2 : EFFECTIFS DES ÉLÈVES ====================
     * Basée sur la section "SECTION 2" de show.blade.php
     */
    protected function createSheet2_Effectifs()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('2. Effectifs');
        
        $row = 1;
        
        // TITRE PRINCIPAL
        $sheet->setCellValue("A{$row}", 'EFFECTIFS DES ÉLÈVES - ' . $this->rapport->annee_scolaire);
        $sheet->mergeCells("A{$row}:E{$row}");
        $this->styleHeader($sheet, $row, '8B5CF6'); // Violet
        
        $row += 2;
        
        if ($this->rapport->effectifs && $this->rapport->effectifs->count() > 0) {
            
            // === EFFECTIFS PAR NIVEAU ===
            $sheet->setCellValue("A{$row}", 'EFFECTIFS PAR NIVEAU');
            $sheet->mergeCells("A{$row}:E{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Niveau')
                  ->setCellValue("B{$row}", 'Classes')
                  ->setCellValue("C{$row}", 'Garçons')
                  ->setCellValue("D{$row}", 'Filles')
                  ->setCellValue("E{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $totalClasses = 0;
            $totalGarcons = 0;
            $totalFilles = 0;
            $totalEffectif = 0;
            
            foreach ($this->rapport->effectifs as $effectif) {
                $totalClasses += $effectif->nombre_classes;
                $totalGarcons += $effectif->effectif_garcons;
                $totalFilles += $effectif->effectif_filles;
                $totalEffectif += $effectif->effectif_total;
                
                $sheet->setCellValue("A{$row}", $effectif->niveau)
                      ->setCellValue("B{$row}", $effectif->nombre_classes)
                      ->setCellValue("C{$row}", $effectif->effectif_garcons)
                      ->setCellValue("D{$row}", $effectif->effectif_filles)
                      ->setCellValue("E{$row}", $effectif->effectif_total);
                $this->styleBold($sheet, "E{$row}");
                $row++;
            }
            
            // Ligne de total
            $sheet->setCellValue("A{$row}", 'TOTAL GÉNÉRAL')
                  ->setCellValue("B{$row}", $totalClasses)
                  ->setCellValue("C{$row}", $totalGarcons)
                  ->setCellValue("D{$row}", $totalFilles)
                  ->setCellValue("E{$row}", $totalEffectif);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            $this->styleBold($sheet, "E{$row}");
            
            $row += 3;
            
            // === REDOUBLANTS PAR NIVEAU ===
            $sheet->setCellValue("A{$row}", 'REDOUBLANTS PAR NIVEAU');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Niveau')
                  ->setCellValue("B{$row}", 'Garçons')
                  ->setCellValue("C{$row}", 'Filles')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $totalRedoublantsG = 0;
            $totalRedoublantsF = 0;
            $totalRedoublants = 0;
            
            foreach ($this->rapport->effectifs as $effectif) {
                $totalRedoublantsG += $effectif->redoublants_garcons ?? 0;
                $totalRedoublantsF += $effectif->redoublants_filles ?? 0;
                $totalRedoublants += $effectif->redoublants_total ?? 0;
                
                $sheet->setCellValue("A{$row}", $effectif->niveau)
                      ->setCellValue("B{$row}", $effectif->redoublants_garcons ?? 0)
                      ->setCellValue("C{$row}", $effectif->redoublants_filles ?? 0)
                      ->setCellValue("D{$row}", $effectif->redoublants_total ?? 0);
                $this->styleBold($sheet, "D{$row}");
                $row++;
            }
            
            // Total redoublants
            $sheet->setCellValue("A{$row}", 'TOTAL')
                  ->setCellValue("B{$row}", $totalRedoublantsG)
                  ->setCellValue("C{$row}", $totalRedoublantsF)
                  ->setCellValue("D{$row}", $totalRedoublants);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            
            $row += 3;
            
            // === ABANDONS PAR NIVEAU ===
            $sheet->setCellValue("A{$row}", 'ABANDONS PAR NIVEAU');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Niveau')
                  ->setCellValue("B{$row}", 'Garçons')
                  ->setCellValue("C{$row}", 'Filles')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $totalAbandonsG = 0;
            $totalAbandonsF = 0;
            $totalAbandons = 0;
            
            foreach ($this->rapport->effectifs as $effectif) {
                $totalAbandonsG += $effectif->abandons_garcons ?? 0;
                $totalAbandonsF += $effectif->abandons_filles ?? 0;
                $totalAbandons += $effectif->abandons_total ?? 0;
                
                $sheet->setCellValue("A{$row}", $effectif->niveau)
                      ->setCellValue("B{$row}", $effectif->abandons_garcons ?? 0)
                      ->setCellValue("C{$row}", $effectif->abandons_filles ?? 0)
                      ->setCellValue("D{$row}", $effectif->abandons_total ?? 0);
                $this->styleBold($sheet, "D{$row}");
                $row++;
            }
            
            // Total abandons
            $sheet->setCellValue("A{$row}", 'TOTAL')
                  ->setCellValue("B{$row}", $totalAbandonsG)
                  ->setCellValue("C{$row}", $totalAbandonsF)
                  ->setCellValue("D{$row}", $totalAbandons);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            
            $row += 3;
            
            // === ÉLÈVES EN SITUATION DE HANDICAP PAR NIVEAU ===
            $totalHandicapMoteurG = $this->rapport->effectifs->sum('handicap_moteur_garcons');
            $totalHandicapMoteurF = $this->rapport->effectifs->sum('handicap_moteur_filles');
            $totalHandicapMoteur = $this->rapport->effectifs->sum('handicap_moteur_total');
            
            $totalHandicapVisuelG = $this->rapport->effectifs->sum('handicap_visuel_garcons');
            $totalHandicapVisuelF = $this->rapport->effectifs->sum('handicap_visuel_filles');
            $totalHandicapVisuel = $this->rapport->effectifs->sum('handicap_visuel_total');
            
            $totalHandicapSourdMuetG = $this->rapport->effectifs->sum('handicap_sourd_muet_garcons');
            $totalHandicapSourdMuetF = $this->rapport->effectifs->sum('handicap_sourd_muet_filles');
            $totalHandicapSourdMuet = $this->rapport->effectifs->sum('handicap_sourd_muet_total');
            
            $totalHandicapDeficienceG = $this->rapport->effectifs->sum('handicap_deficience_intel_garcons');
            $totalHandicapDeficienceF = $this->rapport->effectifs->sum('handicap_deficience_intel_filles');
            $totalHandicapDeficience = $this->rapport->effectifs->sum('handicap_deficience_intel_total');
            
            if ($totalHandicapMoteur + $totalHandicapVisuel + $totalHandicapSourdMuet + $totalHandicapDeficience > 0) {
                $sheet->setCellValue("A{$row}", 'ÉLÈVES EN SITUATION DE HANDICAP PAR NIVEAU');
                $sheet->mergeCells("A{$row}:D{$row}");
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                // HANDICAP MOTEUR
                if ($totalHandicapMoteur > 0) {
                    $sheet->setCellValue("A{$row}", 'Handicap Moteur');
                    $this->styleBold($sheet, "A{$row}");
                    $row++;
                    
                    $sheet->setCellValue("A{$row}", 'Niveau')
                          ->setCellValue("B{$row}", 'Garçons')
                          ->setCellValue("C{$row}", 'Filles')
                          ->setCellValue("D{$row}", 'Total');
                    $this->styleSubHeader($sheet, $row);
                    $row++;
                    
                    $tempTotalG = 0;
                    $tempTotalF = 0;
                    $tempTotal = 0;
                    
                    foreach ($this->rapport->effectifs as $effectif) {
                        if (($effectif->handicap_moteur_total ?? 0) > 0) {
                            $sheet->setCellValue("A{$row}", $effectif->niveau)
                                  ->setCellValue("B{$row}", $effectif->handicap_moteur_garcons ?? 0)
                                  ->setCellValue("C{$row}", $effectif->handicap_moteur_filles ?? 0)
                                  ->setCellValue("D{$row}", $effectif->handicap_moteur_total ?? 0);
                            $tempTotalG += $effectif->handicap_moteur_garcons ?? 0;
                            $tempTotalF += $effectif->handicap_moteur_filles ?? 0;
                            $tempTotal += $effectif->handicap_moteur_total ?? 0;
                            $row++;
                        }
                    }
                    
                    $sheet->setCellValue("A{$row}", 'Total Handicap Moteur')
                          ->setCellValue("B{$row}", $tempTotalG)
                          ->setCellValue("C{$row}", $tempTotalF)
                          ->setCellValue("D{$row}", $tempTotal);
                    $this->styleBold($sheet, "A{$row}");
                    $this->styleBold($sheet, "D{$row}");
                    $row += 2;
                }
                
                // HANDICAP VISUEL
                if ($totalHandicapVisuel > 0) {
                    $sheet->setCellValue("A{$row}", 'Handicap Visuel');
                    $this->styleBold($sheet, "A{$row}");
                    $row++;
                    
                    $sheet->setCellValue("A{$row}", 'Niveau')
                          ->setCellValue("B{$row}", 'Garçons')
                          ->setCellValue("C{$row}", 'Filles')
                          ->setCellValue("D{$row}", 'Total');
                    $this->styleSubHeader($sheet, $row);
                    $row++;
                    
                    $tempTotalG = 0;
                    $tempTotalF = 0;
                    $tempTotal = 0;
                    
                    foreach ($this->rapport->effectifs as $effectif) {
                        if (($effectif->handicap_visuel_total ?? 0) > 0) {
                            $sheet->setCellValue("A{$row}", $effectif->niveau)
                                  ->setCellValue("B{$row}", $effectif->handicap_visuel_garcons ?? 0)
                                  ->setCellValue("C{$row}", $effectif->handicap_visuel_filles ?? 0)
                                  ->setCellValue("D{$row}", $effectif->handicap_visuel_total ?? 0);
                            $tempTotalG += $effectif->handicap_visuel_garcons ?? 0;
                            $tempTotalF += $effectif->handicap_visuel_filles ?? 0;
                            $tempTotal += $effectif->handicap_visuel_total ?? 0;
                            $row++;
                        }
                    }
                    
                    $sheet->setCellValue("A{$row}", 'Total Handicap Visuel')
                          ->setCellValue("B{$row}", $tempTotalG)
                          ->setCellValue("C{$row}", $tempTotalF)
                          ->setCellValue("D{$row}", $tempTotal);
                    $this->styleBold($sheet, "A{$row}");
                    $this->styleBold($sheet, "D{$row}");
                    $row += 2;
                }
                
                // HANDICAP SOURD/MUET
                if ($totalHandicapSourdMuet > 0) {
                    $sheet->setCellValue("A{$row}", 'Handicap Sourd/Muet');
                    $this->styleBold($sheet, "A{$row}");
                    $row++;
                    
                    $sheet->setCellValue("A{$row}", 'Niveau')
                          ->setCellValue("B{$row}", 'Garçons')
                          ->setCellValue("C{$row}", 'Filles')
                          ->setCellValue("D{$row}", 'Total');
                    $this->styleSubHeader($sheet, $row);
                    $row++;
                    
                    $tempTotalG = 0;
                    $tempTotalF = 0;
                    $tempTotal = 0;
                    
                    foreach ($this->rapport->effectifs as $effectif) {
                        if (($effectif->handicap_sourd_muet_total ?? 0) > 0) {
                            $sheet->setCellValue("A{$row}", $effectif->niveau)
                                  ->setCellValue("B{$row}", $effectif->handicap_sourd_muet_garcons ?? 0)
                                  ->setCellValue("C{$row}", $effectif->handicap_sourd_muet_filles ?? 0)
                                  ->setCellValue("D{$row}", $effectif->handicap_sourd_muet_total ?? 0);
                            $tempTotalG += $effectif->handicap_sourd_muet_garcons ?? 0;
                            $tempTotalF += $effectif->handicap_sourd_muet_filles ?? 0;
                            $tempTotal += $effectif->handicap_sourd_muet_total ?? 0;
                            $row++;
                        }
                    }
                    
                    $sheet->setCellValue("A{$row}", 'Total Handicap Sourd/Muet')
                          ->setCellValue("B{$row}", $tempTotalG)
                          ->setCellValue("C{$row}", $tempTotalF)
                          ->setCellValue("D{$row}", $tempTotal);
                    $this->styleBold($sheet, "A{$row}");
                    $this->styleBold($sheet, "D{$row}");
                    $row += 2;
                }
                
                // DÉFICIENCE INTELLECTUELLE
                if ($totalHandicapDeficience > 0) {
                    $sheet->setCellValue("A{$row}", 'Déficience Intellectuelle');
                    $this->styleBold($sheet, "A{$row}");
                    $row++;
                    
                    $sheet->setCellValue("A{$row}", 'Niveau')
                          ->setCellValue("B{$row}", 'Garçons')
                          ->setCellValue("C{$row}", 'Filles')
                          ->setCellValue("D{$row}", 'Total');
                    $this->styleSubHeader($sheet, $row);
                    $row++;
                    
                    $tempTotalG = 0;
                    $tempTotalF = 0;
                    $tempTotal = 0;
                    
                    foreach ($this->rapport->effectifs as $effectif) {
                        if (($effectif->handicap_deficience_intel_total ?? 0) > 0) {
                            $sheet->setCellValue("A{$row}", $effectif->niveau)
                                  ->setCellValue("B{$row}", $effectif->handicap_deficience_intel_garcons ?? 0)
                                  ->setCellValue("C{$row}", $effectif->handicap_deficience_intel_filles ?? 0)
                                  ->setCellValue("D{$row}", $effectif->handicap_deficience_intel_total ?? 0);
                            $tempTotalG += $effectif->handicap_deficience_intel_garcons ?? 0;
                            $tempTotalF += $effectif->handicap_deficience_intel_filles ?? 0;
                            $tempTotal += $effectif->handicap_deficience_intel_total ?? 0;
                            $row++;
                        }
                    }
                    
                    $sheet->setCellValue("A{$row}", 'Total Déficience Intellectuelle')
                          ->setCellValue("B{$row}", $tempTotalG)
                          ->setCellValue("C{$row}", $tempTotalF)
                          ->setCellValue("D{$row}", $tempTotal);
                    $this->styleBold($sheet, "A{$row}");
                    $this->styleBold($sheet, "D{$row}");
                    $row += 2;
                }
                
                // TOTAL GÉNÉRAL HANDICAPS
                $sheet->setCellValue("A{$row}", 'TOTAL GÉNÉRAL HANDICAPS')
                      ->setCellValue("B{$row}", $totalHandicapMoteurG + $totalHandicapVisuelG + $totalHandicapSourdMuetG + $totalHandicapDeficienceG)
                      ->setCellValue("C{$row}", $totalHandicapMoteurF + $totalHandicapVisuelF + $totalHandicapSourdMuetF + $totalHandicapDeficienceF)
                      ->setCellValue("D{$row}", $totalHandicapMoteur + $totalHandicapVisuel + $totalHandicapSourdMuet + $totalHandicapDeficience);
                $this->styleBold($sheet, "A{$row}");
                $this->styleBold($sheet, "B{$row}");
                $this->styleBold($sheet, "C{$row}");
                $this->styleBold($sheet, "D{$row}");
                
                $row += 3;
            }
            
            // === SITUATIONS SPÉCIALES PAR NIVEAU ===
            $totalOrphelinsG = $this->rapport->effectifs->sum('orphelins_garcons');
            $totalOrphelinsF = $this->rapport->effectifs->sum('orphelins_filles');
            $totalOrphelins = $this->rapport->effectifs->sum('orphelins_total');
            
            $totalSansExtraitG = $this->rapport->effectifs->sum('sans_extrait_garcons');
            $totalSansExtraitF = $this->rapport->effectifs->sum('sans_extrait_filles');
            $totalSansExtrait = $this->rapport->effectifs->sum('sans_extrait_total');
            
            if ($totalOrphelins + $totalSansExtrait > 0) {
                $sheet->setCellValue("A{$row}", 'SITUATIONS SPÉCIALES PAR NIVEAU');
                $sheet->mergeCells("A{$row}:D{$row}");
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                // ORPHELINS
                if ($totalOrphelins > 0) {
                    $sheet->setCellValue("A{$row}", 'Orphelins');
                    $this->styleBold($sheet, "A{$row}");
                    $row++;
                    
                    $sheet->setCellValue("A{$row}", 'Niveau')
                          ->setCellValue("B{$row}", 'Garçons')
                          ->setCellValue("C{$row}", 'Filles')
                          ->setCellValue("D{$row}", 'Total');
                    $this->styleSubHeader($sheet, $row);
                    $row++;
                    
                    $tempTotalG = 0;
                    $tempTotalF = 0;
                    $tempTotal = 0;
                    
                    foreach ($this->rapport->effectifs as $effectif) {
                        if (($effectif->orphelins_total ?? 0) > 0) {
                            $sheet->setCellValue("A{$row}", $effectif->niveau)
                                  ->setCellValue("B{$row}", $effectif->orphelins_garcons ?? 0)
                                  ->setCellValue("C{$row}", $effectif->orphelins_filles ?? 0)
                                  ->setCellValue("D{$row}", $effectif->orphelins_total ?? 0);
                            $tempTotalG += $effectif->orphelins_garcons ?? 0;
                            $tempTotalF += $effectif->orphelins_filles ?? 0;
                            $tempTotal += $effectif->orphelins_total ?? 0;
                            $row++;
                        }
                    }
                    
                    $sheet->setCellValue("A{$row}", 'Total Orphelins')
                          ->setCellValue("B{$row}", $tempTotalG)
                          ->setCellValue("C{$row}", $tempTotalF)
                          ->setCellValue("D{$row}", $tempTotal);
                    $this->styleBold($sheet, "A{$row}");
                    $this->styleBold($sheet, "D{$row}");
                    $row += 2;
                }
                
                // SANS EXTRAIT DE NAISSANCE
                if ($totalSansExtrait > 0) {
                    $sheet->setCellValue("A{$row}", 'Sans Extrait de Naissance');
                    $this->styleBold($sheet, "A{$row}");
                    $row++;
                    
                    $sheet->setCellValue("A{$row}", 'Niveau')
                          ->setCellValue("B{$row}", 'Garçons')
                          ->setCellValue("C{$row}", 'Filles')
                          ->setCellValue("D{$row}", 'Total');
                    $this->styleSubHeader($sheet, $row);
                    $row++;
                    
                    $tempTotalG = 0;
                    $tempTotalF = 0;
                    $tempTotal = 0;
                    
                    foreach ($this->rapport->effectifs as $effectif) {
                        if (($effectif->sans_extrait_total ?? 0) > 0) {
                            $sheet->setCellValue("A{$row}", $effectif->niveau)
                                  ->setCellValue("B{$row}", $effectif->sans_extrait_garcons ?? 0)
                                  ->setCellValue("C{$row}", $effectif->sans_extrait_filles ?? 0)
                                  ->setCellValue("D{$row}", $effectif->sans_extrait_total ?? 0);
                            $tempTotalG += $effectif->sans_extrait_garcons ?? 0;
                            $tempTotalF += $effectif->sans_extrait_filles ?? 0;
                            $tempTotal += $effectif->sans_extrait_total ?? 0;
                            $row++;
                        }
                    }
                    
                    $sheet->setCellValue("A{$row}", 'Total Sans Extrait')
                          ->setCellValue("B{$row}", $tempTotalG)
                          ->setCellValue("C{$row}", $tempTotalF)
                          ->setCellValue("D{$row}", $tempTotal);
                    $this->styleBold($sheet, "A{$row}");
                    $this->styleBold($sheet, "D{$row}");
                    $row += 2;
                }
                
                // TOTAL SITUATIONS SPÉCIALES
                $sheet->setCellValue("A{$row}", 'TOTAL SITUATIONS SPÉCIALES')
                      ->setCellValue("B{$row}", $totalOrphelinsG + $totalSansExtraitG)
                      ->setCellValue("C{$row}", $totalOrphelinsF + $totalSansExtraitF)
                      ->setCellValue("D{$row}", $totalOrphelins + $totalSansExtrait);
                $this->styleBold($sheet, "A{$row}");
                $this->styleBold($sheet, "B{$row}");
                $this->styleBold($sheet, "C{$row}");
                $this->styleBold($sheet, "D{$row}");
            }
            
        } else {
            $sheet->setCellValue("A{$row}", 'Aucune donnée d\'effectifs saisie');
            $sheet->mergeCells("A{$row}:E{$row}");
        }
        
        // Ajuster les colonnes
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
    }

    /**
     * ==================== FEUILLE 3 : EXAMENS & RECRUTEMENT ====================
     * Basée sur la section "SECTION 3" de show.blade.php
     */
    protected function createSheet3_ExamensRecrutement()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('3. Examens');
        
        $row = 1;
        
        // TITRE PRINCIPAL
        $sheet->setCellValue("A{$row}", 'EXAMENS ET RECRUTEMENT - ' . $this->rapport->annee_scolaire);
        $sheet->mergeCells("A{$row}:D{$row}");
        $this->styleHeader($sheet, $row, 'F59E0B'); // Orange
        
        $row += 2;
        
        // === CFEE ===
        if ($this->rapport->cfee) {
            $cfee = $this->rapport->cfee;
            
            $sheet->setCellValue("A{$row}", 'RÉSULTATS CFEE (Certificat de Fin d\'Études Élémentaires)');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Catégorie')
                  ->setCellValue("B{$row}", 'Filles')
                  ->setCellValue("C{$row}", 'Total')
                  ->setCellValue("D{$row}", 'Taux');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Candidats')
                  ->setCellValue("B{$row}", $cfee->cfee_candidats_filles ?? 0)
                  ->setCellValue("C{$row}", $cfee->cfee_candidats_total ?? 0)
                  ->setCellValue("D{$row}", '-');
            $this->styleBold($sheet, "C{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Admis')
                  ->setCellValue("B{$row}", $cfee->cfee_admis_filles ?? 0)
                  ->setCellValue("C{$row}", $cfee->cfee_admis_total ?? 0)
                  ->setCellValue("D{$row}", '-');
            $this->styleBold($sheet, "C{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Taux de Réussite')
                  ->setCellValue("B{$row}", number_format($cfee->cfee_taux_reussite_filles ?? 0, 1) . ' %')
                  ->setCellValue("C{$row}", number_format($cfee->cfee_taux_reussite ?? 0, 1) . ' %')
                  ->setCellValue("D{$row}", '-');
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            
            $row += 3;
        }
        
        // === ENTRÉE EN SIXIÈME ===
        if ($this->rapport->entreeSixieme) {
            $sixieme = $this->rapport->entreeSixieme;
            
            $sheet->setCellValue("A{$row}", 'ENTRÉE EN SIXIÈME');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Catégorie')
                  ->setCellValue("B{$row}", 'Filles')
                  ->setCellValue("C{$row}", 'Total')
                  ->setCellValue("D{$row}", 'Taux');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Candidats')
                  ->setCellValue("B{$row}", $sixieme->sixieme_candidats_filles ?? 0)
                  ->setCellValue("C{$row}", $sixieme->sixieme_candidats_total ?? 0)
                  ->setCellValue("D{$row}", '-');
            $this->styleBold($sheet, "C{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Admis')
                  ->setCellValue("B{$row}", $sixieme->sixieme_admis_filles ?? 0)
                  ->setCellValue("C{$row}", $sixieme->sixieme_admis_total ?? 0)
                  ->setCellValue("D{$row}", '-');
            $this->styleBold($sheet, "C{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Taux d\'Admission')
                  ->setCellValue("B{$row}", number_format($sixieme->sixieme_taux_admission_filles ?? 0, 1) . ' %')
                  ->setCellValue("C{$row}", number_format($sixieme->sixieme_taux_admission ?? 0, 1) . ' %')
                  ->setCellValue("D{$row}", '-');
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            
            $row += 3;
        }
        
        // === CMG ===
        if ($this->rapport->cmg) {
            $cmg = $this->rapport->cmg;
            
            $sheet->setCellValue("A{$row}", 'CLASSES MULTIGRADES (CMG)');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Nombre de CMG')->setCellValue("B{$row}", $cmg->cmg_nombre ?? 0);
            $row++;
            
            if ($cmg->cmg_combinaison_1) {
                $sheet->setCellValue("A{$row}", 'Combinaison 1')->setCellValue("B{$row}", $cmg->cmg_combinaison_1);
                $row++;
            }
            if ($cmg->cmg_combinaison_2) {
                $sheet->setCellValue("A{$row}", 'Combinaison 2')->setCellValue("B{$row}", $cmg->cmg_combinaison_2);
                $row++;
            }
            if ($cmg->cmg_combinaison_3) {
                $sheet->setCellValue("A{$row}", 'Combinaison 3')->setCellValue("B{$row}", $cmg->cmg_combinaison_3);
                $row++;
            }
            if ($cmg->cmg_combinaison_autres) {
                $sheet->setCellValue("A{$row}", 'Autres Combinaisons')->setCellValue("B{$row}", $cmg->cmg_combinaison_autres);
                $row++;
            }
            
            $row += 3;
        }
        
        // === RECRUTEMENT CI ===
        if ($this->rapport->recrutementCi) {
            $ci = $this->rapport->recrutementCi;
            
            $sheet->setCellValue("A{$row}", 'RECRUTEMENT CI (Cours d\'Initiation)');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Nombre de CI')->setCellValue("B{$row}", $ci->ci_nombre ?? 0);
            $row++;
            
            if ($ci->ci_combinaison_1) {
                $sheet->setCellValue("A{$row}", 'Combinaison 1')->setCellValue("B{$row}", $ci->ci_combinaison_1);
                $row++;
            }
            if ($ci->ci_combinaison_2) {
                $sheet->setCellValue("A{$row}", 'Combinaison 2')->setCellValue("B{$row}", $ci->ci_combinaison_2);
                $row++;
            }
            if ($ci->ci_combinaison_3) {
                $sheet->setCellValue("A{$row}", 'Combinaison 3')->setCellValue("B{$row}", $ci->ci_combinaison_3);
                $row++;
            }
            if ($ci->ci_combinaison_autres) {
                $sheet->setCellValue("A{$row}", 'Autres Combinaisons')->setCellValue("B{$row}", $ci->ci_combinaison_autres);
                $row++;
            }
            
            if ($ci->ci_statut) {
                $sheet->setCellValue("A{$row}", 'Statut')->setCellValue("B{$row}", ucfirst($ci->ci_statut));
                $row++;
            }
            
            $row += 2;
            
            // Effectifs CI par période
            $sheet->setCellValue("A{$row}", 'Effectifs CI par Période de Recrutement');
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Période')
                  ->setCellValue("B{$row}", 'Garçons')
                  ->setCellValue("C{$row}", 'Filles')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Octobre')
                  ->setCellValue("B{$row}", $ci->ci_octobre_garcons ?? 0)
                  ->setCellValue("C{$row}", $ci->ci_octobre_filles ?? 0)
                  ->setCellValue("D{$row}", $ci->ci_octobre_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Janvier')
                  ->setCellValue("B{$row}", $ci->ci_janvier_garcons ?? 0)
                  ->setCellValue("C{$row}", $ci->ci_janvier_filles ?? 0)
                  ->setCellValue("D{$row}", $ci->ci_janvier_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Mai')
                  ->setCellValue("B{$row}", $ci->ci_mai_garcons ?? 0)
                  ->setCellValue("C{$row}", $ci->ci_mai_filles ?? 0)
                  ->setCellValue("D{$row}", $ci->ci_mai_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            // Total général
            $totalG = ($ci->ci_octobre_garcons ?? 0) + ($ci->ci_janvier_garcons ?? 0) + ($ci->ci_mai_garcons ?? 0);
            $totalF = ($ci->ci_octobre_filles ?? 0) + ($ci->ci_janvier_filles ?? 0) + ($ci->ci_mai_filles ?? 0);
            $total = ($ci->ci_octobre_total ?? 0) + ($ci->ci_janvier_total ?? 0) + ($ci->ci_mai_total ?? 0);
            
            $sheet->setCellValue("A{$row}", 'TOTAL GÉNÉRAL')
                  ->setCellValue("B{$row}", $totalG)
                  ->setCellValue("C{$row}", $totalF)
                  ->setCellValue("D{$row}", $total);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
        }
        
        // Ajuster les colonnes
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
    }

    /**
     * ==================== FEUILLE 4 : PERSONNEL ENSEIGNANT ====================
     * Basée sur la section "SECTION 4" de show.blade.php
     */
    protected function createSheet4_PersonnelEnseignant()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('4. Personnel');
        
        $row = 1;
        
        // TITRE PRINCIPAL
        $sheet->setCellValue("A{$row}", 'PERSONNEL ENSEIGNANT - ' . $this->rapport->annee_scolaire);
        $sheet->mergeCells("A{$row}:D{$row}");
        $this->styleHeader($sheet, $row, 'EF4444'); // Rouge
        
        $row += 2;
        
        if ($this->rapport->personnelEnseignant) {
            $perso = $this->rapport->personnelEnseignant;
            
            // === RÉPARTITION PAR SPÉCIALITÉ ===
            $sheet->setCellValue("A{$row}", 'RÉPARTITION PAR SPÉCIALITÉ');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Spécialité')
                  ->setCellValue("B{$row}", 'Hommes')
                  ->setCellValue("C{$row}", 'Femmes')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Titulaires')
                  ->setCellValue("B{$row}", $perso->titulaires_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->titulaires_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->titulaires_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Vacataires')
                  ->setCellValue("B{$row}", $perso->vacataires_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->vacataires_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->vacataires_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Volontaires')
                  ->setCellValue("B{$row}", $perso->volontaires_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->volontaires_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->volontaires_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Contractuels')
                  ->setCellValue("B{$row}", $perso->contractuels_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->contractuels_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->contractuels_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Communautaires')
                  ->setCellValue("B{$row}", $perso->communautaires_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->communautaires_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->communautaires_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            // Total général
            $sheet->setCellValue("A{$row}", 'TOTAL GÉNÉRAL')
                  ->setCellValue("B{$row}", $perso->total_personnel_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->total_personnel_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->total_personnel ?? 0);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            
            $row += 3;
            
            // === RÉPARTITION PAR CORPS ===
            $sheet->setCellValue("A{$row}", 'RÉPARTITION PAR CORPS');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Corps')
                  ->setCellValue("B{$row}", 'Hommes')
                  ->setCellValue("C{$row}", 'Femmes')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Instituteurs')
                  ->setCellValue("B{$row}", $perso->instituteurs_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->instituteurs_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->instituteurs_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Instituteurs Adjoints')
                  ->setCellValue("B{$row}", $perso->instituteurs_adjoints_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->instituteurs_adjoints_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->instituteurs_adjoints_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Professeurs')
                  ->setCellValue("B{$row}", $perso->professeurs_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->professeurs_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->professeurs_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            // Total
            $totalCorpsH = ($perso->instituteurs_hommes ?? 0) + ($perso->instituteurs_adjoints_hommes ?? 0) + ($perso->professeurs_hommes ?? 0);
            $totalCorpsF = ($perso->instituteurs_femmes ?? 0) + ($perso->instituteurs_adjoints_femmes ?? 0) + ($perso->professeurs_femmes ?? 0);
            $totalCorps = ($perso->instituteurs_total ?? 0) + ($perso->instituteurs_adjoints_total ?? 0) + ($perso->professeurs_total ?? 0);
            
            $sheet->setCellValue("A{$row}", 'TOTAL')
                  ->setCellValue("B{$row}", $totalCorpsH)
                  ->setCellValue("C{$row}", $totalCorpsF)
                  ->setCellValue("D{$row}", $totalCorps);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            
            $row += 3;
            
            // === RÉPARTITION PAR DIPLÔME ===
            $sheet->setCellValue("A{$row}", 'RÉPARTITION PAR DIPLÔME');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Diplôme')
                  ->setCellValue("B{$row}", 'Hommes')
                  ->setCellValue("C{$row}", 'Femmes')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Master')
                  ->setCellValue("B{$row}", $perso->master_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->master_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->master_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Licence')
                  ->setCellValue("B{$row}", $perso->licence_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->licence_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->licence_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'BAC')
                  ->setCellValue("B{$row}", $perso->bac_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->bac_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->bac_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'BFEM')
                  ->setCellValue("B{$row}", $perso->bfem_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->bfem_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->bfem_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'CFEE')
                  ->setCellValue("B{$row}", $perso->cfee_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->cfee_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->cfee_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Autres Diplômes')
                  ->setCellValue("B{$row}", $perso->autres_diplomes_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->autres_diplomes_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->autres_diplomes_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            // Total diplômes
            $totalDipH = ($perso->master_hommes ?? 0) + ($perso->licence_hommes ?? 0) + ($perso->bac_hommes ?? 0) + 
                         ($perso->bfem_hommes ?? 0) + ($perso->cfee_hommes ?? 0) + ($perso->autres_diplomes_hommes ?? 0);
            $totalDipF = ($perso->master_femmes ?? 0) + ($perso->licence_femmes ?? 0) + ($perso->bac_femmes ?? 0) + 
                         ($perso->bfem_femmes ?? 0) + ($perso->cfee_femmes ?? 0) + ($perso->autres_diplomes_femmes ?? 0);
            $totalDip = ($perso->master_total ?? 0) + ($perso->licence_total ?? 0) + ($perso->bac_total ?? 0) + 
                        ($perso->bfem_total ?? 0) + ($perso->cfee_total ?? 0) + ($perso->autres_diplomes_total ?? 0);
            
            $sheet->setCellValue("A{$row}", 'TOTAL')
                  ->setCellValue("B{$row}", $totalDipH)
                  ->setCellValue("C{$row}", $totalDipF)
                  ->setCellValue("D{$row}", $totalDip);
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            
            $row += 3;
            
            // === COMPÉTENCES TIC ===
            $sheet->setCellValue("A{$row}", 'COMPÉTENCES TIC');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Compétence')
                  ->setCellValue("B{$row}", 'Hommes')
                  ->setCellValue("C{$row}", 'Femmes')
                  ->setCellValue("D{$row}", 'Total');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Enseignants Formés aux TIC')
                  ->setCellValue("B{$row}", $perso->enseignants_formes_tic_hommes ?? 0)
                  ->setCellValue("C{$row}", $perso->enseignants_formes_tic_femmes ?? 0)
                  ->setCellValue("D{$row}", $perso->enseignants_formes_tic_total ?? 0);
            $this->styleBold($sheet, "D{$row}");
            
            $row += 3;
            
            // === STATISTIQUES GÉNÉRALES ===
            $sheet->setCellValue("A{$row}", 'STATISTIQUES GÉNÉRALES');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Total Personnel Enseignant')
                  ->setCellValue("B{$row}", $perso->total_personnel ?? 0);
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Taux de Féminisation')
                  ->setCellValue("B{$row}", number_format($perso->taux_feminisation ?? 0, 1) . ' %');
            $this->styleBold($sheet, "B{$row}");
            $row++;
            
            if ($perso->ratio_eleves_enseignant) {
                $sheet->setCellValue("A{$row}", 'Ratio Élèves/Enseignant')
                      ->setCellValue("B{$row}", number_format($perso->ratio_eleves_enseignant, 1));
                $this->styleBold($sheet, "B{$row}");
            }
        }
        
        // Ajuster les colonnes
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
    }

    /**
     * ==================== FEUILLE 5 : MATÉRIEL PÉDAGOGIQUE ====================
     * Basée sur la section "SECTION 5" de show.blade.php
     */
    protected function createSheet5_MaterielPedagogique()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('5. Matériel Pédago');
        
        $row = 1;
        
        // TITRE PRINCIPAL
        $sheet->setCellValue("A{$row}", 'MATÉRIEL PÉDAGOGIQUE - ' . $this->rapport->annee_scolaire);
        $sheet->mergeCells("A{$row}:E{$row}");
        $this->styleHeader($sheet, $row, '3B82F6'); // Bleu
        
        $row += 2;
        
        // === MANUELS ÉLÈVES PAR NIVEAU ===
        if ($this->rapport->manuelsEleves && $this->rapport->manuelsEleves->count() > 0) {
            $sheet->setCellValue("A{$row}", 'MANUELS ÉLÈVES PAR NIVEAU');
            $sheet->mergeCells("A{$row}:L{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Niveau')
                  ->setCellValue("B{$row}", 'LC Français')
                  ->setCellValue("C{$row}", 'Maths')
                  ->setCellValue("D{$row}", 'EDD')
                  ->setCellValue("E{$row}", 'DM')
                  ->setCellValue("F{$row}", 'Manuel Classe')
                  ->setCellValue("G{$row}", 'Livret Maison')
                  ->setCellValue("H{$row}", 'Livret Gradué')
                  ->setCellValue("I{$row}", 'Planche Alpha')
                  ->setCellValue("J{$row}", 'Arabe')
                  ->setCellValue("K{$row}", 'Religion')
                  ->setCellValue("L{$row}", 'Autres');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            foreach ($this->rapport->manuelsEleves as $manuel) {
                $sheet->setCellValue("A{$row}", $manuel->niveau)
                      ->setCellValue("B{$row}", $manuel->lc_francais ?? 0)
                      ->setCellValue("C{$row}", $manuel->mathematiques ?? 0)
                      ->setCellValue("D{$row}", $manuel->edd ?? 0)
                      ->setCellValue("E{$row}", $manuel->dm ?? 0)
                      ->setCellValue("F{$row}", $manuel->manuel_classe ?? 0)
                      ->setCellValue("G{$row}", $manuel->livret_maison ?? 0)
                      ->setCellValue("H{$row}", $manuel->livret_devoir_gradue ?? 0)
                      ->setCellValue("I{$row}", $manuel->planche_alphabetique ?? 0)
                      ->setCellValue("J{$row}", $manuel->manuel_arabe ?? 0)
                      ->setCellValue("K{$row}", $manuel->manuel_religion ?? 0)
                      ->setCellValue("L{$row}", $manuel->autres_manuels ?? 0);
                $this->styleBold($sheet, "A{$row}");
                $row++;
            }
            
            $row += 2;
        }
        
        // === MANUELS DU MAÎTRE PAR NIVEAU ===
        if ($this->rapport->manuelsMaitre && $this->rapport->manuelsMaitre->count() > 0) {
            $sheet->setCellValue("A{$row}", 'MANUELS DU MAÎTRE PAR NIVEAU');
            $sheet->mergeCells("A{$row}:I{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Niveau')
                  ->setCellValue("B{$row}", 'Guide LC Français')
                  ->setCellValue("C{$row}", 'Guide Maths')
                  ->setCellValue("D{$row}", 'Guide EDD')
                  ->setCellValue("E{$row}", 'Guide DM')
                  ->setCellValue("F{$row}", 'Guide Péda')
                  ->setCellValue("G{$row}", 'Guide Arabe/Religion')
                  ->setCellValue("H{$row}", 'Guide Langue Nat.')
                  ->setCellValue("I{$row}", 'Autres');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            foreach ($this->rapport->manuelsMaitre as $manuel) {
                $sheet->setCellValue("A{$row}", $manuel->niveau)
                      ->setCellValue("B{$row}", $manuel->guide_lc_francais ?? 0)
                      ->setCellValue("C{$row}", $manuel->guide_mathematiques ?? 0)
                      ->setCellValue("D{$row}", $manuel->guide_edd ?? 0)
                      ->setCellValue("E{$row}", $manuel->guide_dm ?? 0)
                      ->setCellValue("F{$row}", $manuel->guide_pedagogique ?? 0)
                      ->setCellValue("G{$row}", $manuel->guide_arabe_religieux ?? 0)
                      ->setCellValue("H{$row}", $manuel->guide_langue_nationale ?? 0)
                      ->setCellValue("I{$row}", $manuel->autres_manuels_maitre ?? 0);
                $this->styleBold($sheet, "A{$row}");
                $row++;
            }
            
            $row += 2;
        }
        
        // === DICTIONNAIRES ===
        if ($this->rapport->dictionnaires) {
            $dico = $this->rapport->dictionnaires;
            
            $sheet->setCellValue("A{$row}", 'DICTIONNAIRES');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Type de Dictionnaire')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            // Français
            $sheet->setCellValue("A{$row}", 'Dictionnaires Français')
                  ->setCellValue("B{$row}", $dico->dico_francais_total ?? 0)
                  ->setCellValue("C{$row}", $dico->dico_francais_bon_etat ?? 0);
            if (($dico->dico_francais_total ?? 0) > 0) {
                $pct = number_format((($dico->dico_francais_bon_etat ?? 0) / $dico->dico_francais_total) * 100, 1);
                $sheet->setCellValue("D{$row}", $pct . ' %');
            } else {
                $sheet->setCellValue("D{$row}", '-');
            }
            $row++;
            
            // Arabe
            $sheet->setCellValue("A{$row}", 'Dictionnaires Arabe')
                  ->setCellValue("B{$row}", $dico->dico_arabe_total ?? 0)
                  ->setCellValue("C{$row}", $dico->dico_arabe_bon_etat ?? 0);
            if (($dico->dico_arabe_total ?? 0) > 0) {
                $pct = number_format((($dico->dico_arabe_bon_etat ?? 0) / $dico->dico_arabe_total) * 100, 1);
                $sheet->setCellValue("D{$row}", $pct . ' %');
            } else {
                $sheet->setCellValue("D{$row}", '-');
            }
            $row++;
            
            // Autres
            $sheet->setCellValue("A{$row}", 'Autres Dictionnaires')
                  ->setCellValue("B{$row}", $dico->dico_autre_total ?? 0)
                  ->setCellValue("C{$row}", $dico->dico_autre_bon_etat ?? 0);
            if (($dico->dico_autre_total ?? 0) > 0) {
                $pct = number_format((($dico->dico_autre_bon_etat ?? 0) / $dico->dico_autre_total) * 100, 1);
                $sheet->setCellValue("D{$row}", $pct . ' %');
            } else {
                $sheet->setCellValue("D{$row}", '-');
            }
            $row++;
            
            // Total
            $totalDico = ($dico->dico_francais_total ?? 0) + ($dico->dico_arabe_total ?? 0) + ($dico->dico_autre_total ?? 0);
            $totalBonEtat = ($dico->dico_francais_bon_etat ?? 0) + ($dico->dico_arabe_bon_etat ?? 0) + ($dico->dico_autre_bon_etat ?? 0);
            
            $sheet->setCellValue("A{$row}", 'TOTAL GÉNÉRAL')
                  ->setCellValue("B{$row}", $totalDico)
                  ->setCellValue("C{$row}", $totalBonEtat);
            if ($totalDico > 0) {
                $pct = number_format(($totalBonEtat / $totalDico) * 100, 1);
                $sheet->setCellValue("D{$row}", $pct . ' %');
            } else {
                $sheet->setCellValue("D{$row}", '0 %');
            }
            $this->styleBold($sheet, "A{$row}");
            $this->styleBold($sheet, "B{$row}");
            $this->styleBold($sheet, "C{$row}");
            $this->styleBold($sheet, "D{$row}");
            $row++;
            
            // Infos supplémentaires
            if ($dico->besoins_dictionnaires) {
                $row++;
                $sheet->setCellValue("A{$row}", 'Besoins:')
                      ->setCellValue("B{$row}", $dico->besoins_dictionnaires);
                $sheet->mergeCells("B{$row}:L{$row}");
                $this->styleBold($sheet, "A{$row}");
            }
            if ($dico->budget_estime_dictionnaires) {
                $row++;
                $sheet->setCellValue("A{$row}", 'Budget Estimé:')
                      ->setCellValue("B{$row}", number_format($dico->budget_estime_dictionnaires, 0, ',', ' ') . ' FCFA');
                $this->styleBold($sheet, "A{$row}");
            }
            if ($dico->observations_dictionnaires) {
                $row++;
                $sheet->setCellValue("A{$row}", 'Observations:')
                      ->setCellValue("B{$row}", $dico->observations_dictionnaires);
                $sheet->mergeCells("B{$row}:L{$row}");
                $this->styleBold($sheet, "A{$row}");
            }
            
            $row += 2;
        }
        
        // === MATÉRIEL DIDACTIQUE ===
        if ($this->rapport->materielDidactique) {
            $mat = $this->rapport->materielDidactique;
            
            $sheet->setCellValue("A{$row}", 'MATÉRIEL DIDACTIQUE');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row += 2;
            
            // === Instruments de Géométrie ===
            $sheet->setCellValue("A{$row}", 'Instruments de Géométrie');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Matériel')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $geometrie = [
                ['Règles Plates', 'regle_plate'],
                ['Équerres', 'equerre'],
                ['Compas', 'compas'],
                ['Rapporteurs', 'rapporteur'],
                ['Décamètres', 'decametre'],
                ['Chaînes d\'Arpenteur', 'chaine_arpenteur'],
            ];
            
            foreach ($geometrie as $item) {
                [$label, $field] = $item;
                $total = $mat->{$field . '_total'} ?? 0;
                $bonEtat = $mat->{$field . '_bon_etat'} ?? 0;
                
                $sheet->setCellValue("A{$row}", $label)
                      ->setCellValue("B{$row}", $total)
                      ->setCellValue("C{$row}", $bonEtat);
                if ($total > 0) {
                    $pct = number_format(($bonEtat / $total) * 100, 1);
                    $sheet->setCellValue("D{$row}", $pct . ' %');
                } else {
                    $sheet->setCellValue("D{$row}", '0 %');
                }
                $row++;
            }
            
            $row += 2;
            
            // === Instruments Scientifiques ===
            $sheet->setCellValue("A{$row}", 'Instruments Scientifiques');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Matériel')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $scientifiques = [
                ['Boussoles', 'boussole'],
                ['Thermomètres', 'thermometre'],
                ['Kits de Capacité', 'kit_capacite'],
                ['Balances', 'balance'],
                ['Kits Matériel Scientifique', 'kit_materiel_scientifique'],
            ];
            
            foreach ($scientifiques as $item) {
                [$label, $field] = $item;
                $total = $mat->{$field . '_total'} ?? 0;
                $bonEtat = $mat->{$field . '_bon_etat'} ?? 0;
                
                $sheet->setCellValue("A{$row}", $label)
                      ->setCellValue("B{$row}", $total)
                      ->setCellValue("C{$row}", $bonEtat);
                if ($total > 0) {
                    $pct = number_format(($bonEtat / $total) * 100, 1);
                    $sheet->setCellValue("D{$row}", $pct . ' %');
                } else {
                    $sheet->setCellValue("D{$row}", '0 %');
                }
                $row++;
            }
            
            $row += 2;
            
            // === Supports Pédagogiques ===
            $sheet->setCellValue("A{$row}", 'Supports Pédagogiques');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Matériel')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $supports = [
                ['Globes Terrestres', 'globe'],
                ['Cartes Murales', 'cartes_murales'],
                ['Planches Illustrées', 'planches_illustrees'],
                ['Autres Matériels', 'autres_materiel'],
            ];
            
            foreach ($supports as $item) {
                [$label, $field] = $item;
                $total = $mat->{$field . '_total'} ?? 0;
                $bonEtat = $mat->{$field . '_bon_etat'} ?? 0;
                
                $sheet->setCellValue("A{$row}", $label)
                      ->setCellValue("B{$row}", $total)
                      ->setCellValue("C{$row}", $bonEtat);
                if ($total > 0) {
                    $pct = number_format(($bonEtat / $total) * 100, 1);
                    $sheet->setCellValue("D{$row}", $pct . ' %');
                } else {
                    $sheet->setCellValue("D{$row}", '0 %');
                }
                $row++;
            }
        }
        
        // Ajuster les colonnes
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
    }

    /**
     * ==================== FEUILLE 6 : INFRASTRUCTURE & ÉQUIPEMENTS ====================
     * Basée sur la section "SECTION 6" de show.blade.php
     */
    protected function createSheet6_InfrastructureEquipements()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('6. Infrastructure');
        
        $row = 1;
        
        // TITRE PRINCIPAL
        $sheet->setCellValue("A{$row}", 'INFRASTRUCTURE & ÉQUIPEMENTS - ' . $this->rapport->annee_scolaire);
        $sheet->mergeCells("A{$row}:D{$row}");
        $this->styleHeader($sheet, $row, '6366F1'); // Indigo
        
        $row += 2;
        
        // === CAPITAL IMMOBILIER ===
        if ($this->rapport->capitalImmobilier) {
            $immo = $this->rapport->capitalImmobilier;
            
            $sheet->setCellValue("A{$row}", 'CAPITAL IMMOBILIER');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row += 2;
            
            // === Salles de Classe ===
            $sheet->setCellValue("A{$row}", 'Salles de Classe et Espaces d\'Enseignement');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Infrastructure')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sallesClasse = [
                ['Salles en Dur', 'salles_dur'],
                ['Abris Provisoires', 'abris_provisoires'],
            ];
            
            foreach ($sallesClasse as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $immo, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Espaces Administratifs ===
            $sheet->setCellValue("A{$row}", 'Espaces Administratifs et de Service');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Infrastructure')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $espacesAdmin = [
                ['Blocs Administratifs', 'bloc_admin'],
                ['Magasins', 'magasin'],
                ['Salles Informatiques', 'salle_informatique'],
                ['Bibliothèques', 'salle_bibliotheque'],
            ];
            
            foreach ($espacesAdmin as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $immo, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Restauration ===
            $sheet->setCellValue("A{$row}", 'Restauration');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Infrastructure')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $restauration = [
                ['Cuisines', 'cuisine'],
                ['Réfectoires', 'refectoire'],
            ];
            
            foreach ($restauration as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $immo, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Sanitaires ===
            $sheet->setCellValue("A{$row}", 'Sanitaires');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Infrastructure')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $sanitaires = [
                ['Toilettes Enseignants', 'toilettes_enseignants'],
                ['Toilettes Garçons', 'toilettes_garcons'],
                ['Toilettes Filles', 'toilettes_filles'],
                ['Toilettes Mixtes', 'toilettes_mixtes'],
            ];
            
            foreach ($sanitaires as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $immo, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Logements ===
            $sheet->setCellValue("A{$row}", 'Logements');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Infrastructure')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $logements = [
                ['Logement Directeur', 'logement_directeur'],
                ['Logement Gardien', 'logement_gardien'],
            ];
            
            foreach ($logements as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $immo, $field);
                $row++;
            }
            
            // Autres infrastructures
            if (($immo->autres_infrastructures_total ?? 0) > 0) {
                $row++;
                $sheet->setCellValue("A{$row}", 'Autres Infrastructures');
                $sheet->mergeCells("A{$row}:D{$row}");
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                $sheet->setCellValue("A{$row}", 'Infrastructure')
                      ->setCellValue("B{$row}", 'Total')
                      ->setCellValue("C{$row}", 'Bon État')
                      ->setCellValue("D{$row}", '% Bon État');
                $this->styleSubHeader($sheet, $row);
                $row++;
                
                $this->addInfraRow($sheet, $row, 'Autres', $immo, 'autres_infrastructures');
                $row++;
            }
            
            $row += 2;
        }
        
        // === CAPITAL MOBILIER ===
        if ($this->rapport->capitalMobilier) {
            $mob = $this->rapport->capitalMobilier;
            
            $sheet->setCellValue("A{$row}", 'CAPITAL MOBILIER');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row += 2;
            
            // === Mobilier Élèves ===
            $sheet->setCellValue("A{$row}", 'Mobilier Élèves');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Type')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $mobilierEleves = [
                ['Tables-Bancs', 'tables_bancs'],
                ['Chaises Élèves', 'chaises_eleves'],
                ['Tables Individuelles', 'tables_individuelles'],
            ];
            
            foreach ($mobilierEleves as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $mob, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Mobilier Enseignants ===
            $sheet->setCellValue("A{$row}", 'Mobilier Enseignants');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Type')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $mobilierEnseignants = [
                ['Bureaux Maître', 'bureaux_maitre'],
                ['Chaises Maître', 'chaises_maitre'],
                ['Tableaux', 'tableaux'],
                ['Armoires', 'armoires'],
            ];
            
            foreach ($mobilierEnseignants as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $mob, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Mobilier Administratif ===
            $sheet->setCellValue("A{$row}", 'Mobilier Administratif');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Type')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $mobilierAdmin = [
                ['Bureaux Administratifs', 'bureaux_admin'],
                ['Chaises Administratives', 'chaises_admin'],
            ];
            
            foreach ($mobilierAdmin as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $mob, $field);
                $row++;
            }
            
            // === Équipements Divers (optionnels) ===
            $hasDivers = ($mob->mat_drapeau_total ?? 0) > 0 || ($mob->drapeau_total ?? 0) > 0 ||
                         ($mob->tableaux_mobiles_total ?? 0) > 0 || ($mob->tableaux_interactifs_total ?? 0) > 0 ||
                         ($mob->tableaux_muraux_total ?? 0) > 0;
            
            if ($hasDivers) {
                $row += 2;
                $sheet->setCellValue("A{$row}", 'Équipements Divers');
                $sheet->mergeCells("A{$row}:D{$row}");
                $this->styleBold($sheet, "A{$row}");
                $row++;
                
                $sheet->setCellValue("A{$row}", 'Type')
                      ->setCellValue("B{$row}", 'Total')
                      ->setCellValue("C{$row}", 'Bon État')
                      ->setCellValue("D{$row}", '% Bon État');
                $this->styleSubHeader($sheet, $row);
                $row++;
                
                if (($mob->tableaux_mobiles_total ?? 0) > 0) {
                    $this->addInfraRow($sheet, $row, 'Tableaux Mobiles', $mob, 'tableaux_mobiles');
                    $row++;
                }
                if (($mob->tableaux_interactifs_total ?? 0) > 0) {
                    $this->addInfraRow($sheet, $row, 'Tableaux Interactifs', $mob, 'tableaux_interactifs');
                    $row++;
                }
                if (($mob->tableaux_muraux_total ?? 0) > 0) {
                    $this->addInfraRow($sheet, $row, 'Tableaux Muraux', $mob, 'tableaux_muraux');
                    $row++;
                }
                if (($mob->mat_drapeau_total ?? 0) > 0) {
                    $this->addInfraRow($sheet, $row, 'Mâts de Drapeau', $mob, 'mat_drapeau');
                    $row++;
                }
                if (($mob->drapeau_total ?? 0) > 0) {
                    $this->addInfraRow($sheet, $row, 'Drapeaux', $mob, 'drapeau');
                    $row++;
                }
            }
            
            $row += 2;
        }
        
        // === ÉQUIPEMENT INFORMATIQUE ===
        if ($this->rapport->equipementInformatique) {
            $equip = $this->rapport->equipementInformatique;
            
            $sheet->setCellValue("A{$row}", 'ÉQUIPEMENT INFORMATIQUE');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row += 2;
            
            // === Matériel Informatique ===
            $sheet->setCellValue("A{$row}", 'Matériel Informatique');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Équipement')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $materielInfo = [
                ['Ordinateurs Fixes', 'ordinateurs_fixes'],
                ['Ordinateurs Portables', 'ordinateurs_portables'],
                ['Tablettes', 'tablettes'],
                ['Vidéoprojecteurs', 'videoprojecteurs'],
            ];
            
            foreach ($materielInfo as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $equip, $field);
                $row++;
            }
            
            $row += 2;
            
            // === Matériel d'Impression ===
            $sheet->setCellValue("A{$row}", 'Matériel d\'Impression');
            $sheet->mergeCells("A{$row}:D{$row}");
            $this->styleBold($sheet, "A{$row}");
            $row++;
            
            $sheet->setCellValue("A{$row}", 'Équipement')
                  ->setCellValue("B{$row}", 'Total')
                  ->setCellValue("C{$row}", 'Bon État')
                  ->setCellValue("D{$row}", '% Bon État');
            $this->styleSubHeader($sheet, $row);
            $row++;
            
            $materielImpression = [
                ['Imprimantes Laser', 'imprimantes_laser'],
                ['Imprimantes Jet d\'Encre', 'imprimantes_jet_encre'],
                ['Imprimantes Multifonction', 'imprimantes_multifonction'],
                ['Photocopieuses', 'photocopieuses'],
            ];
            
            foreach ($materielImpression as $item) {
                [$label, $field] = $item;
                $this->addInfraRow($sheet, $row, $label, $equip, $field);
                $row++;
            }
            
            // Autres équipements
            if (($equip->autres_equipements_total ?? 0) > 0) {
                $this->addInfraRow($sheet, $row, 'Autres Équipements', $equip, 'autres_equipements');
            }
        }
        
        // Ajuster les colonnes
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
    }

    /**
     * Méthode helper pour ajouter une ligne d'infrastructure
     */
    protected function addInfraRow($sheet, $row, $label, $model, $field)
    {
        $total = $model->{$field . '_total'} ?? 0;
        $bonEtat = $model->{$field . '_bon_etat'} ?? 0;
        
        $sheet->setCellValue("A{$row}", $label)
              ->setCellValue("B{$row}", $total)
              ->setCellValue("C{$row}", $bonEtat);
        
        if ($total > 0) {
            $pct = number_format(($bonEtat / $total) * 100, 1);
            $sheet->setCellValue("D{$row}", $pct . ' %');
        } else {
            $sheet->setCellValue("D{$row}", '0 %');
        }
    }
}
