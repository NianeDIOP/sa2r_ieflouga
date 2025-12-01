<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RapportExcelTemplateService
{
    private $spreadsheet;
    
    /**
     * Générer le template Excel vierge pour import de rapports
     */
    public function generateTemplate()
    {
        $this->spreadsheet = new Spreadsheet();
        
        // Supprimer la feuille par défaut
        $this->spreadsheet->removeSheetByIndex(0);
        
        // Créer les 7 onglets (Instructions + 6 étapes)
        $this->createInstructionsSheet();
        $this->createEtape1Sheet();
        $this->createEtape2Sheet();
        $this->createEtape3Sheet();
        $this->createEtape4Sheet();
        $this->createEtape5Sheet();
        $this->createEtape6Sheet();
        
        // Définir l'onglet Instructions comme actif
        $this->spreadsheet->setActiveSheetIndex(0);
        
        // Métadonnées du fichier
        $this->spreadsheet->getProperties()
            ->setCreator('SA2R - IEF LOUGA')
            ->setTitle('Template Rapport de Rentrée - Import Excel')
            ->setDescription('Template Excel pour saisie offline des rapports de rentrée des établissements')
            ->setSubject('Rapport de Rentrée Scolaire')
            ->setKeywords('rapport rentrée excel import éducation sénégal')
            ->setCategory('Éducation')
            ->setCompany('Ministère de l\'Éducation Nationale - IEF Louga');
        
        return $this->spreadsheet;
    }
    
    /**
     * Sauvegarder le template dans un fichier
     */
    public function saveTemplate($filePath)
    {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);
    }
    
    /**
     * Télécharger le template directement
     */
    public function downloadTemplate($filename = 'template_rapport_rentree.xlsx')
    {
        // Métadonnées du fichier
        $this->spreadsheet->getProperties()
            ->setTitle('Template Rapport Rentree')
            ->setSubject('Rapport de Rentree Scolaire')
            ->setDescription('Template pour la saisie offline des rapports de rentree');
        
        // IMPORTANT: Configurer le calcul automatique dans Excel
        $this->spreadsheet->getProperties()->setManager('IEF Louga');
        
        $writer = new Xlsx($this->spreadsheet);
        
        // NE PAS pré-calculer (cause des problèmes avec les instructions contenant ">")
        // Les formules seront calculées par Excel à l'ouverture
        $writer->setPreCalculateFormulas(false);
        
        // Créer un fichier temporaire
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
    
    // =================== ONGLET 0 - INSTRUCTIONS ===================
    
    private function createInstructionsSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('INSTRUCTIONS');
        
        // En-tête principal
        $sheet->setCellValue('A1', 'TEMPLATE EXCEL - RAPPORT DE RENTRÉE SCOLAIRE');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1', '1976D2', 16, true);
        
        $sheet->setCellValue('A2', 'IEF LOUGA - Année Scolaire 2024-2025');
        $sheet->mergeCells('A2:F2');
        $this->styleSubHeader($sheet, 'A2');
        
        // Instructions
        $row = 4;
        $instructions = [
            ['INSTRUCTIONS GENERALES', '', 'bold'],
            ['', '', ''],
            ['1.', 'Remplissez UNIQUEMENT les cellules en VERT CLAIR ou BLANC', ''],
            ['2.', 'Les cellules BLEUES sont calculees automatiquement - NE PAS MODIFIER', ''],
            ['3.', 'Les cellules NOIRES sont INACTIVES - Ne les remplissez pas', ''],
            ['4.', 'Respectez les formats imposes (nombres, listes deroulantes, etc.)', ''],
            ['5.', 'Les champs obligatoires sont marques d\'un asterisque (*)', ''],
            ['', '', ''],
            ['LEGENDE DES COULEURS', '', 'bold'],
            ['', '', ''],
            ['VERT CLAIR', 'Cellules de SAISIE - Remplissez ces champs', ''],
            ['BLEU CLAIR', 'Cellules AUTO-CALCULEES - Ne pas modifier (formules)', ''],
            ['BLANC', 'Cellules de SAISIE - Remplissez selon votre situation', ''],
            ['', '', ''],
            ['IMPORTANT: CHAMPS CONDITIONNELS', '', 'bold'],
            ['', '', ''],
            ['', 'Certains champs dependent de vos choix OUI/NON:', ''],
            ['', '=> Si vous choisissez NON, laissez les champs associes VIDES', ''],
            ['', '=> Si vous choisissez OUI, remplissez TOUS les champs associes', ''],
            ['', '', ''],
            ['', 'Exemples:', ''],
            ['', '- Cloture: NON => Ne remplissez pas Etat Cloture, laissez vide', ''],
            ['', '- Cloture: OUI => Remplissez obligatoirement Etat Cloture', ''],
            ['', '', ''],
            ['STRUCTURE DU TEMPLATE', '', 'bold'],
            ['', '', ''],
            ['Etape 1', 'Informations Generales (Directeur, Infrastructures, Structures, Langues, Finances)', ''],
            ['Etape 2', 'Effectifs par niveau (CI, CP, CE1, CE2, CM1, CM2)', ''],
            ['Etape 3', 'Examens et Recrutement (CMG, CFEE, Entree 6eme, CI)', ''],
            ['Etape 4', 'Personnel Enseignant (Specialite, Corps, Diplomes, TIC)', ''],
            ['Etape 5', 'Materiel Pedagogique (Manuels, Dictionnaires, Materiel)', ''],
            ['Etape 6', 'Infrastructure (Capital Immobilier, Mobilier, Equipements)', ''],
            ['', '', ''],
            ['VALIDATION DES DONNEES', '', 'bold'],
            ['', '', ''],
            ['Contacts', 'Format: 9 chiffres commencant par 77, 78, 76, 70 ou 75', ''],
            ['Emails', 'Format: exemple@domaine.sn (doit contenir @ et .)', ''],
            ['Noms', 'Minimum 3 caracteres, lettres uniquement, au moins 2 mots', ''],
            ['Nombres', 'Uniquement des valeurs positives (sup ou egal a 0)', ''],
            ['Listes', 'Selectionnez dans les listes deroulantes proposees', ''],
            ['', '', ''],
            ['ERREURS FREQUENTES A EVITER', '', 'bold'],
            ['', '', ''],
            ['X', 'Modifier les cellules BLEUES (totaux auto-calcules)', ''],
            ['X', 'Remplir les cellules NOIRES (champs inactifs)', ''],
            ['X', 'Saisir des valeurs negatives', ''],
            ['X', 'Entrer du texte dans les champs numeriques', ''],
            ['X', 'Depasser les limites (ex: Bon etat sup a Total)', ''],
            ['X', 'Supprimer ou renommer les onglets', ''],
            ['', '', ''],
            ['SAUVEGARDE', '', 'bold'],
            ['', '', ''],
            ['', 'Enregistrez regulierement votre travail (Ctrl+S)', ''],
            ['', 'Une fois termine, envoyez le fichier a l\'IEF pour import', ''],
            ['', 'L\'IEF validera automatiquement vos donnees lors de l\'import', ''],
            ['', '', ''],
            ['SUPPORT', '', 'bold'],
            ['', '', ''],
            ['', 'En cas de probleme, contactez l\'IEF de Louga', ''],
            ['', 'Email: ief.louga@education.sn', ''],
            ['', 'Telephone: 77 XXX XX XX', ''],
        ];
        
        foreach ($instructions as $instruction) {
            // Forcer le type TEXT pour éviter que PhpSpreadsheet interprète comme formule
            $sheet->setCellValueExplicit('A' . $row, $instruction[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $row, $instruction[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            if (isset($instruction[2]) && $instruction[2] === 'bold') {
                $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A' . $row . ':B' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E3F2FD');
            }
            
            $row++;
        }
        
        // Largeurs des colonnes
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(70);
        
        // Hauteur de ligne pour l'en-tête
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Protection totale de la feuille
        $sheet->getProtection()->setSheet(true);
    }
    
    // =================== ONGLET 1 - INFORMATIONS GÉNÉRALES ===================
    
    private function createEtape1Sheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Etape 1 - Infos');
        
        // En-tête
        $sheet->setCellValue('A1', 'ETAPE 1 - INFORMATIONS GENERALES');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1', '4CAF50');
        
        $row = 3;
        
        // Section 1.1 - Info Directeur
        $this->createSectionHeader($sheet, $row, 'A', '1.1 INFORMATIONS DU DIRECTEUR *', '66BB6A');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Nom Complet du Directeur *');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('D' . $row, 'Min 3 caracteres, LETTRES UNIQUEMENT, min 2 mots');
        $this->styleInputCell($sheet, 'B' . $row);
        $sheet->mergeCells('B' . $row . ':C' . $row);
        // Validation: min 3 caractères + pas de chiffres
        $this->addTextOnlyValidation($sheet, 'B' . $row, 3, 100);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Contact Principal *');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('D' . $row, '9 chiffres: 77/78/76/70/75 OBLIGATOIRE');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addPhoneValidation($sheet, 'B' . $row, false); // obligatoire
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Contact Secondaire');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('D' . $row, '9 chiffres (optionnel, different du principal)');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addPhoneValidation($sheet, 'B' . $row, true); // optionnel
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Email');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('D' . $row, 'Format: exemple@domaine.sn (avec @ et .)');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addEmailValidation($sheet, 'B' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Distance au siege IEF (km)');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('D' . $row, 'Nombre positif, 1 decimale max (0-500)');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $row += 2;
        
        // Section 1.2 - Infrastructures de Base
        $this->createSectionHeader($sheet, $row, 'A', '1.2 INFRASTRUCTURES DE BASE', '66BB6A');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Infrastructure');
        $sheet->setCellValue('B' . $row, 'Existe');
        $sheet->setCellValue('C' . $row, 'Type/Nombre');
        $sheet->setCellValue('D' . $row, 'REGLE');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        // CPE - avec nombre
        $cpeExisteRow = $row;
        $sheet->setCellValue('A' . $row, 'CPE (Case Tout-Petits)');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, 'Saisir Type UNIQUEMENT si Existe=OUI');
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row); // Conditionnel sur B
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 50);
        $row++;
        
        // Clôture - avec types
        $sheet->setCellValue('A' . $row, 'Cloture');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, 'Si Existe=NON, laissez vide');
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $this->addDropdownValidation($sheet, 'C' . $row, ['', 'Dur', 'Provisoire', 'Haie', 'Autre']);
        $row++;
        
        // Eau - avec types
        $sheet->setCellValue('A' . $row, 'Eau');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, 'Si Existe=NON, laissez vide');
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $this->addDropdownValidation($sheet, 'C' . $row, ['', 'Robinet', 'Forage', 'Puits', 'Autre']);
        $row++;
        
        // Électricité - avec types
        $sheet->setCellValue('A' . $row, 'Electricite');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, 'Si Existe=NON, laissez vide');
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $this->addDropdownValidation($sheet, 'C' . $row, ['', 'SENELEC', 'Solaire', 'Groupe electrogene', 'Autre']);
        $row++;
        
        // Internet - avec types
        $sheet->setCellValue('A' . $row, 'Connexion Internet');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, 'Si Existe=NON, laissez vide');
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $this->addDropdownValidation($sheet, 'C' . $row, ['', 'Fibre optique', '4G/5G', 'Satellite', 'Autre']);
        $row++;
        
        // Cantine - avec gestion
        $sheet->setCellValue('A' . $row, 'Cantine Scolaire');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('C' . $row, '');
        $sheet->setCellValue('D' . $row, 'Si Existe=NON, laissez vide');
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $this->addDropdownValidation($sheet, 'C' . $row, ['', 'Etat', 'Partenaire', 'Communaute', 'Autre']);
        $row += 2;
        
        // Section 1.3 - Structures Communautaires
        $this->createSectionHeader($sheet, $row, 'A', '1.3 STRUCTURES COMMUNAUTAIRES', '66BB6A');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Structure');
        $sheet->setCellValue('B' . $row, 'Existe');
        $sheet->setCellValue('C' . $row, 'Hommes/Garcons');
        $sheet->setCellValue('D' . $row, 'Femmes/Filles');
        $sheet->setCellValue('E' . $row, 'President Nom');
        $sheet->setCellValue('F' . $row, 'President Contact');
        $this->styleTableHeader($sheet, 'A' . $row . ':F' . $row);
        $row++;
        
        $structures = [
            ['CGE (Comite Gestion Ecole)', 'Membres'],
            ['Gouvernement Scolaire', 'Eleves'],
            ['APE (Assoc Parents Eleves)', 'Membres'],
            ['AME (Assoc Meres Eleveuses)', 'Membres']
        ];
        
        foreach ($structures as $struct) {
            $sheet->setCellValue('A' . $row, $struct[0]);
            $sheet->setCellValue('B' . $row, 'NON');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '0');
            $sheet->setCellValue('E' . $row, '');
            $sheet->setCellValue('F' . $row, '');
            $sheet->setCellValue('G' . $row, 'Si Existe=NON, laissez tous vides/0');
            
            $existeCell = 'B' . $row;
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleConditionalInputCell($sheet, 'C' . $row, $existeCell);
            $this->styleConditionalInputCell($sheet, 'D' . $row, $existeCell);
            $this->styleConditionalInputCell($sheet, 'E' . $row, $existeCell);
            $this->styleConditionalInputCell($sheet, 'F' . $row, $existeCell);
            
            $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
            $this->addNumberValidation($sheet, 'D' . $row, 0, 500);
            // Nom: lettres uniquement
            $this->addTextValidation($sheet, 'E' . $row);
            // Téléphone
            $this->addPhoneValidation($sheet, 'F' . $row, true);
            
            $row++;
        }
        $row += 2;
        
        // Section 1.4 - Langues et Projets
        $this->createSectionHeader($sheet, $row, 'A', '1.4 LANGUES ET PROJETS', '66BB6A');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Langue Nationale');
        $sheet->setCellValue('B' . $row, 'Aucune');
        $sheet->setCellValue('D' . $row, 'Selectionnez la langue');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['Aucune', 'Wolof', 'Pulaar', 'Serer', 'Mandinka', 'Soninke', 'Autre']);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Enseignement Arabe');
        $sheet->setCellValue('B' . $row, 'NON');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Projets Informatiques');
        $sheet->setCellValue('B' . $row, 'NON');
        $sheet->setCellValue('D' . $row, 'Si OUI, details obligatoires');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
        $projetRow = $row;
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Details Projets *');
        $sheet->setCellValue('B' . $row, '');
        $sheet->setCellValue('D' . $row, 'Si Projet=NON ci-dessus, laissez vide');
        $this->styleConditionalInputCell($sheet, 'B' . $row, 'B' . $projetRow); // Dépend de la ligne précédente
        $sheet->mergeCells('B' . $row . ':C' . $row);
        $row += 2;
        
        // Section 1.5 - Ressources Financières
        $this->createSectionHeader($sheet, $row, 'A', '1.5 RESSOURCES FINANCIERES', '66BB6A');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Source');
        $sheet->setCellValue('B' . $row, 'Recue');
        $sheet->setCellValue('C' . $row, 'Montant (FCFA)');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $sources = [
            'Subvention Etat',
            'Partenaires',
            'Collectivites Locales',
            'Communaute',
            'Ressources Generees (AGR)'
        ];
        
        $startFinanceRow = $row;
        foreach ($sources as $source) {
            $sheet->setCellValue('A' . $row, $source);
            $sheet->setCellValue('B' . $row, 'NON');
            // NE RIEN METTRE dans la cellule C - Excel traitera les cellules vides comme 0 dans SUM()
            // Mais la formule SUM calculera correctement dès qu'on entre une valeur
            $sheet->setCellValue('D' . $row, 'Si Recue=NON, laissez vide ou 0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleConditionalInputCell($sheet, 'C' . $row, 'B' . $row); // Le montant dépend de "Recue?"
            
            // IMPORTANT: Forcer le format numérique pour les calculs
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
            
            $this->addDropdownValidation($sheet, 'B' . $row, ['OUI', 'NON']);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 99999999);
            
            $row++;
        }
        
        // Total des ressources (calculé automatiquement par Excel)
        $sheet->setCellValue('A' . $row, 'TOTAL RESSOURCES');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        
        // CRITIQUE: Mettre la formule DIRECTEMENT dans la cellule via Cell->setValue()
        // Cela évite les problèmes de conversion en texte
        $cell = $sheet->getCell('C' . $row);
        $cell->setValue('=SUM(C' . $startFinanceRow . ':C' . ($row - 1) . ')');
        
        // Ensuite appliquer le style (après la formule!)
        $sheet->getStyle('C' . $row)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BBDEFB'], // Bleu clair pour formule calculée
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
            ],
            'font' => ['bold' => true],
            'numberFormat' => ['formatCode' => '#,##0'], // Format numérique avec séparateurs
        ]);
        
        // Verrouiller la cellule (contient une formule, ne doit pas être modifiée)
        $sheet->getStyle('C' . $row)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
        
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F5E9'], // Vert clair pour le label
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ],
        ]);
        
        // Largeurs colonnes
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(20);
        
        // Protection (déverrouiller les cellules de saisie)
        $this->protectSheet($sheet);
    }
    
    // =================== ONGLET 2 - EFFECTIFS ===================
    
    private function createEtape2Sheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Etape 2 - Effectifs');
        
        // En-tête
        $sheet->setCellValue('A1', 'ETAPE 2 - EFFECTIFS PAR NIVEAU');
        $sheet->mergeCells('A1:O1');
        $this->styleHeader($sheet, 'A1', '2196F3');
        
        $row = 3;
        $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
        
        // Section 2.1 - Effectifs Totaux & Nombre de Classes
        $this->createSectionHeader($sheet, $row, 'A', '2.1 EFFECTIFS TOTAUX & NOMBRE DE CLASSES', '42A5F5');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Niveau');
        $sheet->setCellValue('B' . $row, 'Garcons *');
        $sheet->setCellValue('C' . $row, 'Filles *');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $sheet->setCellValue('E' . $row, 'Nb Classes *');
        $this->styleTableHeader($sheet, 'A' . $row . ':E' . $row);
        $row++;
        
        $startEffectifRow = $row;
        foreach ($niveaux as $niveau) {
            $sheet->setCellValue('A' . $row, $niveau);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
            $sheet->setCellValue('E' . $row, '0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            $this->styleCalculatedCell($sheet, 'D' . $row);
            $this->styleInputCell($sheet, 'E' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 9999);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 9999);
            $this->addNumberValidation($sheet, 'E' . $row, 0, 20);
            
            $row++;
        }
        
        // Ligne totale
        $sheet->setCellValue('A' . $row, 'TOTAL ECOLE');
        $sheet->setCellValue('B' . $row, '=SUM(B' . $startEffectifRow . ':B' . ($row - 1) . ')');
        $sheet->setCellValue('C' . $row, '=SUM(C' . $startEffectifRow . ':C' . ($row - 1) . ')');
        $sheet->setCellValue('D' . $row, '=SUM(D' . $startEffectifRow . ':D' . ($row - 1) . ')');
        $sheet->setCellValue('E' . $row, '=SUM(E' . $startEffectifRow . ':E' . ($row - 1) . ')');
        
        $this->styleCalculatedCell($sheet, 'B' . $row);
        $this->styleCalculatedCell($sheet, 'C' . $row);
        $this->styleCalculatedCell($sheet, 'D' . $row);
        $this->styleCalculatedCell($sheet, 'E' . $row);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        
        $row += 3;
        
        // Section 2.2 - Redoublants
        $this->createSectionHeader($sheet, $row, 'A', '2.2 REDOUBLANTS', '42A5F5');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Niveau');
        $sheet->setCellValue('B' . $row, 'Garcons');
        $sheet->setCellValue('C' . $row, 'Filles');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        foreach ($niveaux as $niveau) {
            $sheet->setCellValue('A' . $row, $niveau);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            $this->styleCalculatedCell($sheet, 'D' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
            
            $row++;
        }
        
        $row += 2;
        
        // Section 2.3 - Abandons
        $this->createSectionHeader($sheet, $row, 'A', '2.3 ABANDONS', '42A5F5');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Niveau');
        $sheet->setCellValue('B' . $row, 'Garcons');
        $sheet->setCellValue('C' . $row, 'Filles');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        foreach ($niveaux as $niveau) {
            $sheet->setCellValue('A' . $row, $niveau);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            $this->styleCalculatedCell($sheet, 'D' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
            
            $row++;
        }
        
        $row += 2;
        
        // Section 2.4 - Handicaps
        $this->createSectionHeader($sheet, $row, 'A', '2.4 SITUATIONS DE HANDICAP', '42A5F5');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Niveau');
        $sheet->setCellValue('B' . $row, 'Moteur G');
        $sheet->setCellValue('C' . $row, 'Moteur F');
        $sheet->setCellValue('D' . $row, 'Visuel G');
        $sheet->setCellValue('E' . $row, 'Visuel F');
        $sheet->setCellValue('F' . $row, 'Sourd-Muet G');
        $sheet->setCellValue('G' . $row, 'Sourd-Muet F');
        $sheet->setCellValue('H' . $row, 'Defic.Int G');
        $sheet->setCellValue('I' . $row, 'Defic.Int F');
        $this->styleTableHeader($sheet, 'A' . $row . ':I' . $row);
        $row++;
        
        foreach ($niveaux as $niveau) {
            $sheet->setCellValue('A' . $row, $niveau);
            
            for ($col = 'B'; $col <= 'I'; $col++) {
                $sheet->setCellValue($col . $row, '0');
                $this->styleInputCell($sheet, $col . $row);
                $this->addNumberValidation($sheet, $col . $row, 0, 100);
            }
            
            $row++;
        }
        
        $row += 2;
        
        // Section 2.5 - Situations Spéciales
        $this->createSectionHeader($sheet, $row, 'A', '2.5 SITUATIONS SPECIALES', '42A5F5');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Niveau');
        $sheet->setCellValue('B' . $row, 'Orphelins G');
        $sheet->setCellValue('C' . $row, 'Orphelins F');
        $sheet->setCellValue('D' . $row, 'Sans Extrait G');
        $sheet->setCellValue('E' . $row, 'Sans Extrait F');
        $this->styleTableHeader($sheet, 'A' . $row . ':E' . $row);
        $row++;
        
        foreach ($niveaux as $niveau) {
            $sheet->setCellValue('A' . $row, $niveau);
            
            for ($col = 'B'; $col <= 'E'; $col++) {
                $sheet->setCellValue($col . $row, '0');
                $this->styleInputCell($sheet, $col . $row);
                $this->addNumberValidation($sheet, $col . $row, 0, 500);
            }
            
            $row++;
        }
        
        // Largeurs colonnes
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(13);
        
        $this->protectSheet($sheet);
    }
    
    // =================== MÉTHODES UTILITAIRES DE STYLE ===================
    
    private function styleHeader($sheet, $range, $color = '1976D2', $fontSize = 14, $white = true)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => $fontSize,
                'color' => ['rgb' => $white ? 'FFFFFF' : '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $color],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        $rowNum = (int)filter_var($range, FILTER_SANITIZE_NUMBER_INT);
        $sheet->getRowDimension($rowNum)->setRowHeight(25);
    }
    
    private function styleSubHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['italic' => true, 'size' => 11, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
    }
    
    private function createSectionHeader($sheet, $row, $col, $title, $color = '4CAF50')
    {
        $endCol = chr(ord($col) + 5); // 6 colonnes de large
        $sheet->setCellValue($col . $row, $title);
        $sheet->mergeCells($col . $row . ':' . $endCol . $row);
        $sheet->getStyle($col . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $color],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(20);
    }
    
    private function styleTableHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '607D8B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ],
        ]);
    }
    
    private function styleInputCell($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F5E9'], // Vert très clair
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
            ],
        ]);
        
        // Déverrouiller pour saisie
        $sheet->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
    }
    
    /**
     * Style pour cellule conditionnelle (dépend d'une autre cellule OUI/NON)
     * Simple mise en forme BLANC - La validation sera faite côté serveur
     * Plus professionnel que les mises en forme conditionnelles complexes
     */
    private function styleConditionalInputCell($sheet, $cell, $conditionCell)
    {
        // Style simple et propre : fond blanc avec bordure fine
        $sheet->getStyle($cell)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF'], // Blanc = tous les champs disponibles
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
            ],
        ]);
        
        // Déverrouiller pour permettre la saisie
        // La validation de cohérence (si OUI/NON) sera faite côté serveur lors de l'import
        $sheet->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
    }
    
    private function styleCalculatedCell($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BBDEFB'], // Bleu clair
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
            ],
            'font' => ['bold' => true],
        ]);
        
        // Verrouiller (contient formule)
        $sheet->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
    }
    
    // =================== VALIDATIONS ===================
    
    /**
     * Validation pour nom (lettres uniquement, min 3 caractères, min 2 mots)
     */
    private function addTextOnlyValidation($sheet, $cell, $minLength = 3, $maxLength = 100)
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_CUSTOM);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Nom Invalide');
        $validation->setError('Le nom doit contenir au moins ' . $minLength . ' caractères, UNIQUEMENT des lettres et espaces, et au moins 2 mots (pas de chiffres)');
        $validation->setPromptTitle('Nom Complet');
        $validation->setPrompt('Saisissez le nom complet (minimum 2 mots, lettres uniquement)');
        
        // Formule: longueur >= minLength ET contient au moins un espace (2 mots min) ET pas de chiffres
        $validation->setFormula1('AND(LEN(' . $cell . ')>=' . $minLength . ', LEN(TRIM(' . $cell . '))-LEN(SUBSTITUTE(' . $cell . '," ",""))>=1, ISERROR(FIND("0",' . $cell . ')), ISERROR(FIND("1",' . $cell . ')), ISERROR(FIND("2",' . $cell . ')), ISERROR(FIND("3",' . $cell . ')), ISERROR(FIND("4",' . $cell . ')), ISERROR(FIND("5",' . $cell . ')), ISERROR(FIND("6",' . $cell . ')), ISERROR(FIND("7",' . $cell . ')), ISERROR(FIND("8",' . $cell . ')), ISERROR(FIND("9",' . $cell . ')))');
    }
    
    /**
     * Validation email stricte (doit contenir @ et .)
     */
    private function addEmailValidation($sheet, $cell)
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_CUSTOM);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Email Invalide');
        $validation->setError('L\'email doit contenir un @ et un point (.) - Format: exemple@domaine.sn');
        $validation->setPromptTitle('Adresse Email');
        $validation->setPrompt('Format: utilisateur@domaine.sn');
        
        // Formule: contient @ ET contient .
        $validation->setFormula1('AND(NOT(ISERROR(FIND("@",' . $cell . '))), NOT(ISERROR(FIND(".",' . $cell . '))))');
    }
    
    /**
     * Validation pour texte simple (lettres et espaces, optionnel)
     */
    private function addTextValidation($sheet, $cell, $allowBlank = true)
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_CUSTOM);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank($allowBlank);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Texte Invalide');
        $validation->setError('Lettres uniquement (pas de chiffres)');
        $validation->setPromptTitle('Nom');
        $validation->setPrompt('Saisissez des lettres uniquement');
        
        // Formule: pas de chiffres
        $validation->setFormula1('AND(ISERROR(FIND("0",' . $cell . ')), ISERROR(FIND("1",' . $cell . ')), ISERROR(FIND("2",' . $cell . ')), ISERROR(FIND("3",' . $cell . ')), ISERROR(FIND("4",' . $cell . ')), ISERROR(FIND("5",' . $cell . ')), ISERROR(FIND("6",' . $cell . ')), ISERROR(FIND("7",' . $cell . ')), ISERROR(FIND("8",' . $cell . ')), ISERROR(FIND("9",' . $cell . ')))');
    }
    
    private function addPhoneValidation($sheet, $cell, $allowBlank = false)
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_CUSTOM);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank($allowBlank);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Contact Invalide');
        $validation->setError('Le contact doit contenir exactement 9 chiffres et commencer par 77, 78, 76, 70 ou 75');
        $validation->setPromptTitle('Format Contact');
        $validation->setPrompt('Saisissez 9 chiffres (ex: 771234567)' . ($allowBlank ? ' - Optionnel' : ' - OBLIGATOIRE'));
        
        // Formule: longueur=9 ET commence par 77/78/76/70/75
        $validation->setFormula1('AND(LEN(' . $cell . ')=9, OR(LEFT(' . $cell . ',2)="77", LEFT(' . $cell . ',2)="78", LEFT(' . $cell . ',2)="76", LEFT(' . $cell . ',2)="70", LEFT(' . $cell . ',2)="75"))');
    }
    
    private function addNumberValidation($sheet, $cell, $min = 0, $max = 999999)
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setOperator(DataValidation::OPERATOR_BETWEEN);
        $validation->setFormula1($min);
        $validation->setFormula2($max);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Valeur Invalide');
        $validation->setError('Saisissez un nombre entre ' . $min . ' et ' . $max);
        $validation->setPromptTitle('Nombre');
        $validation->setPrompt('Valeur entre ' . $min . ' et ' . $max);
    }
    
    private function addDropdownValidation($sheet, $cell, array $options)
    {
        $validation = $sheet->getCell($cell)->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Selection Invalide');
        $validation->setError('Veuillez selectionner une valeur dans la liste');
        $validation->setPromptTitle('Liste de choix');
        $validation->setPrompt('Selectionnez une option');
        $validation->setFormula1('"' . implode(',', $options) . '"');
    }
    
    private function protectSheet($sheet, $password = '')
    {
        $sheet->getProtection()->setSheet(true);
        if ($password) {
            $sheet->getProtection()->setPassword($password);
        }
    }
    
    // =================== ONGLET 3 - EXAMENS ===================
    
    private function createEtape3Sheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Etape 3 - Examens');
        
        $sheet->setCellValue('A1', 'ETAPE 3 - EXAMENS ET RECRUTEMENT');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1', 'FF9800');
        
        $row = 3;
        
        // Section 3.1 - CMG
        $this->createSectionHeader($sheet, $row, 'A', '3.1 CMG (Concours du Meilleur Garcon)', 'FFA726');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Nombre de participants CMG');
        $sheet->setCellValue('B' . $row, '0');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Combinaison 1');
        $sheet->setCellValue('B' . $row, '');
        $this->styleInputCell($sheet, 'B' . $row);
        $sheet->mergeCells('B' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Combinaison 2');
        $sheet->setCellValue('B' . $row, '');
        $this->styleInputCell($sheet, 'B' . $row);
        $sheet->mergeCells('B' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Combinaison 3');
        $sheet->setCellValue('B' . $row, '');
        $this->styleInputCell($sheet, 'B' . $row);
        $sheet->mergeCells('B' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Autres combinaisons');
        $sheet->setCellValue('B' . $row, '');
        $this->styleInputCell($sheet, 'B' . $row);
        $sheet->mergeCells('B' . $row . ':F' . $row);
        $row += 2;
        
        // Section 3.2 - CFEE
        $this->createSectionHeader($sheet, $row, 'A', '3.2 CFEE (Certificat de Fin d\'Etudes Elementaires)', 'FFA726');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Donnee');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Filles');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Candidats presentes');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Admis');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
        $row += 2;
        
        // Section 3.3 - Entrée Sixième
        $this->createSectionHeader($sheet, $row, 'A', '3.3 ENTREE EN SIXIEME', 'FFA726');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Donnee');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Filles');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Candidats presentes');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Admis');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
        $row += 2;
        
        // Section 3.4 - Recrutement CI
        $this->createSectionHeader($sheet, $row, 'A', '3.4 RECRUTEMENT CI', 'FFA726');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Periode');
        $sheet->setCellValue('B' . $row, 'Garcons');
        $sheet->setCellValue('C' . $row, 'Filles');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Recrutement Octobre');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->styleCalculatedCell($sheet, 'D' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Recrutement Mai');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->styleCalculatedCell($sheet, 'D' . $row);
        $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
        
        // Largeurs colonnes
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        
        $this->protectSheet($sheet);
    }
    // =================== ONGLET 4 - PERSONNEL ===================
    
    private function createEtape4Sheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Etape 4 - Personnel');
        
        $sheet->setCellValue('A1', 'ETAPE 4 - PERSONNEL ENSEIGNANT');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1', '9C27B0');
        
        $row = 3;
        
        // Section 4.1 - Répartition par Spécialité
        $this->createSectionHeader($sheet, $row, 'A', '4.1 REPARTITION PAR SPECIALITE', 'AB47BC');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Specialite');
        $sheet->setCellValue('B' . $row, 'Hommes');
        $sheet->setCellValue('C' . $row, 'Femmes');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        $specialites = [
            'Titulaires',
            'Vacataires',
            'Volontaires',
            'Contractuels',
            'Communautaires'
        ];
        
        $startSpecRow = $row;
        foreach ($specialites as $spec) {
            $sheet->setCellValue('A' . $row, $spec);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            $this->styleCalculatedCell($sheet, 'D' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 200);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 200);
            
            $row++;
        }
        
        // Total général
        $sheet->setCellValue('A' . $row, 'TOTAL GENERAL');
        $sheet->setCellValue('B' . $row, '=SUM(B' . $startSpecRow . ':B' . ($row - 1) . ')');
        $sheet->setCellValue('C' . $row, '=SUM(C' . $startSpecRow . ':C' . ($row - 1) . ')');
        $sheet->setCellValue('D' . $row, '=SUM(D' . $startSpecRow . ':D' . ($row - 1) . ')');
        $this->styleCalculatedCell($sheet, 'B' . $row);
        $this->styleCalculatedCell($sheet, 'C' . $row);
        $this->styleCalculatedCell($sheet, 'D' . $row);
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        
        $totalGeneralRow = $row;
        $row += 2;
        
        // Section 4.2 - Répartition par Corps
        $this->createSectionHeader($sheet, $row, 'A', '4.2 REPARTITION PAR CORPS', 'AB47BC');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Corps');
        $sheet->setCellValue('B' . $row, 'Hommes');
        $sheet->setCellValue('C' . $row, 'Femmes');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        $corps = ['Instituteurs', 'Instituteurs Adjoints', 'Professeurs'];
        
        foreach ($corps as $c) {
            $sheet->setCellValue('A' . $row, $c);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            $this->styleCalculatedCell($sheet, 'D' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 200);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 200);
            
            $row++;
        }
        $row++;
        
        // Section 4.3 - Répartition par Diplômes
        $this->createSectionHeader($sheet, $row, 'A', '4.3 REPARTITION PAR DIPLOMES', 'AB47BC');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Diplome');
        $sheet->setCellValue('B' . $row, 'Hommes');
        $sheet->setCellValue('C' . $row, 'Femmes');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        $diplomes = ['BAC', 'BFEM', 'CFEE', 'Licence', 'Master', 'Autres'];
        
        foreach ($diplomes as $d) {
            $sheet->setCellValue('A' . $row, $d);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            $this->styleCalculatedCell($sheet, 'D' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 200);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 200);
            
            $row++;
        }
        $row++;
        
        // Section 4.4 - Compétences TIC
        $this->createSectionHeader($sheet, $row, 'A', '4.4 COMPETENCES TIC', 'AB47BC');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Enseignants formes TIC');
        $sheet->setCellValue('B' . $row, 'Hommes');
        $sheet->setCellValue('C' . $row, 'Femmes');
        $sheet->setCellValue('D' . $row, 'TOTAL (Auto)');
        $this->styleTableHeader($sheet, 'A' . $row . ':D' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Nombre d\'enseignants formes');
        $sheet->setCellValue('B' . $row, '0');
        $sheet->setCellValue('C' . $row, '0');
        $sheet->setCellValue('D' . $row, '=B' . $row . '+C' . $row);
        
        $this->styleInputCell($sheet, 'B' . $row);
        $this->styleInputCell($sheet, 'C' . $row);
        $this->styleCalculatedCell($sheet, 'D' . $row);
        
        $this->addNumberValidation($sheet, 'B' . $row, 0, 200);
        $this->addNumberValidation($sheet, 'C' . $row, 0, 200);
        
        // Largeurs colonnes
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(18);
        
        $this->protectSheet($sheet);
    }
    // =================== ONGLET 5 - MATÉRIEL PÉDAGOGIQUE ===================
    
    private function createEtape5Sheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Etape 5 - Materiel');
        
        $sheet->setCellValue('A1', 'ETAPE 5 - MATERIEL PEDAGOGIQUE');
        $sheet->mergeCells('A1:H1');
        $this->styleHeader($sheet, 'A1', 'F44336');
        
        $row = 3;
        
        // Section 5.1 - Manuels Élèves (simplifié - 13 types par niveau)
        $this->createSectionHeader($sheet, $row, 'A', '5.1 MANUELS ELEVES (par niveau: CI, CP, CE1, CE2, CM1, CM2)', 'E57373');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Matiere');
        $sheet->setCellValue('B' . $row, 'CI');
        $sheet->setCellValue('C' . $row, 'CP');
        $sheet->setCellValue('D' . $row, 'CE1');
        $sheet->setCellValue('E' . $row, 'CE2');
        $sheet->setCellValue('F' . $row, 'CM1');
        $sheet->setCellValue('G' . $row, 'CM2');
        $this->styleTableHeader($sheet, 'A' . $row . ':G' . $row);
        $row++;
        
        $matieres = [
            'LC Francais',
            'Mathematiques',
            'EDD (Eveil)',
            'DM (Decouverte Monde)',
            'Manuel classe',
            'Livret maison',
            'Livret devoir gradue',
            'Planche alphabetique',
            'Manuel Arabe',
            'Manuel Religion',
            'Autre Religion',
            'Autres manuels'
        ];
        
        foreach ($matieres as $matiere) {
            $sheet->setCellValue('A' . $row, $matiere);
            
            for ($col = 'B'; $col <= 'G'; $col++) {
                $sheet->setCellValue($col . $row, '0');
                $this->styleInputCell($sheet, $col . $row);
                $this->addNumberValidation($sheet, $col . $row, 0, 1000);
            }
            
            $row++;
        }
        $row++;
        
        // Section 5.2 - Manuels Maître (9 guides)
        $this->createSectionHeader($sheet, $row, 'A', '5.2 GUIDES DU MAITRE (par niveau)', 'E57373');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Guide');
        $sheet->setCellValue('B' . $row, 'CI');
        $sheet->setCellValue('C' . $row, 'CP');
        $sheet->setCellValue('D' . $row, 'CE1');
        $sheet->setCellValue('E' . $row, 'CE2');
        $sheet->setCellValue('F' . $row, 'CM1');
        $sheet->setCellValue('G' . $row, 'CM2');
        $this->styleTableHeader($sheet, 'A' . $row . ':G' . $row);
        $row++;
        
        $guides = [
            'LC Francais',
            'Mathematiques',
            'EDD (Eveil)',
            'DM (Decouverte Monde)',
            'Guide Pedagogique',
            'Arabe/Religieux',
            'Langue Nationale',
            'Cahier Recits',
            'Autres guides maitre'
        ];
        
        foreach ($guides as $guide) {
            $sheet->setCellValue('A' . $row, $guide);
            
            for ($col = 'B'; $col <= 'G'; $col++) {
                $sheet->setCellValue($col . $row, '0');
                $this->styleInputCell($sheet, $col . $row);
                $this->addNumberValidation($sheet, $col . $row, 0, 100);
            }
            
            $row++;
        }
        $row++;
        
        // Section 5.3 - Dictionnaires
        $this->createSectionHeader($sheet, $row, 'A', '5.3 DICTIONNAIRES', 'E57373');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Type');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Bon Etat');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $dictionnaires = ['Francais', 'Arabe', 'Autre'];
        
        foreach ($dictionnaires as $dico) {
            $sheet->setCellValue('A' . $row, $dico);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 1000);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 1000);
            
            $row++;
        }
        $row++;
        
        // Section 5.4 - Matériel Didactique
        $this->createSectionHeader($sheet, $row, 'A', '5.4 MATERIEL DIDACTIQUE', 'E57373');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Materiel');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Bon Etat');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $materiels = [
            'Dictionnaires Francais',
            'Dictionnaires Arabe',
            'Dictionnaires Autres',
            'Regle plate',
            'Equerre',
            'Compas',
            'Rapporteur',
            'Decametre',
            'Chaine arpenteur',
            'Boussole',
            'Thermometre',
            'Kit capacite',
            'Balance',
            'Globe',
            'Cartes murales',
            'Planches illustrees',
            'Kit materiel scientifique',
            'Autres materiel'
        ];
        
        foreach ($materiels as $mat) {
            $sheet->setCellValue('A' . $row, $mat);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
            
            $row++;
        }
        
        // Largeurs colonnes
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(12);
        
        $this->protectSheet($sheet);
    }
    // =================== ONGLET 6 - INFRASTRUCTURE ===================
    
    private function createEtape6Sheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Etape 6 - Infrastructure');
        
        $sheet->setCellValue('A1', 'ETAPE 6 - INFRASTRUCTURE & EQUIPEMENTS');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1', '795548');
        
        $row = 3;
        
        // Section 6.1 - Capital Immobilier
        $this->createSectionHeader($sheet, $row, 'A', '6.1 CAPITAL IMMOBILIER', '8D6E63');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Infrastructure');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Bon Etat');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $immobiliers = [
            'Salles en dur',
            'Abris provisoires',
            'Bloc administratif',
            'Magasin',
            'Salle informatique',
            'Salle bibliotheque',
            'Cuisine',
            'Refectoire',
            'Toilettes enseignants',
            'Toilettes garcons',
            'Toilettes filles',
            'Toilettes mixtes',
            'Logement directeur',
            'Logement gardien',
            'Autres infrastructures'
        ];
        
        foreach ($immobiliers as $immo) {
            $sheet->setCellValue('A' . $row, $immo);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 500);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 500);
            
            $row++;
        }
        $row++;
        
        // Section 6.2 - Capital Mobilier
        $this->createSectionHeader($sheet, $row, 'A', '6.2 CAPITAL MOBILIER', '8D6E63');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Mobilier');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Bon Etat');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $mobiliers = [
            'Tables-bancs eleves',
            'Chaises eleves',
            'Tables individuelles',
            'Bureaux maitre',
            'Chaises maitre',
            'Tableaux',
            'Armoires',
            'Bureaux administratifs',
            'Chaises administratives',
            'Bureaux generaux',
            'Chaises generales',
            'Tableaux mobiles',
            'Tableaux interactifs',
            'Tableaux muraux',
            'Mat drapeau',
            'Drapeau'
        ];
        
        foreach ($mobiliers as $mob) {
            $sheet->setCellValue('A' . $row, $mob);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 1000);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 1000);
            
            $row++;
        }
        $row++;
        
        // Section 6.3 - Équipements Informatiques
        $this->createSectionHeader($sheet, $row, 'A', '6.3 EQUIPEMENTS INFORMATIQUES', '8D6E63');
        $row += 2;
        
        $sheet->setCellValue('A' . $row, 'Equipement');
        $sheet->setCellValue('B' . $row, 'Total');
        $sheet->setCellValue('C' . $row, 'Bon Etat');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);
        $row++;
        
        $equipements = [
            'Ordinateurs',
            'Ordinateurs portables',
            'Tablettes',
            'Imprimantes',
            'Photocopieuses',
            'Projecteurs',
            'Scanners',
            'Onduleurs',
            'Autres equipements'
        ];
        
        foreach ($equipements as $equip) {
            $sheet->setCellValue('A' . $row, $equip);
            $sheet->setCellValue('B' . $row, '0');
            $sheet->setCellValue('C' . $row, '0');
            
            $this->styleInputCell($sheet, 'B' . $row);
            $this->styleInputCell($sheet, 'C' . $row);
            
            $this->addNumberValidation($sheet, 'B' . $row, 0, 200);
            $this->addNumberValidation($sheet, 'C' . $row, 0, 200);
            
            $row++;
        }
        
        // Largeurs colonnes
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        
        $this->protectSheet($sheet);
    }
}
