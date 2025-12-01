<?php

namespace App\Exports;

use App\Models\Rapport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class RapportHistoriqueExport
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport->load([
            'etablissement',
            'infoDirecteur',
            'infrastructures',
            'structuresCommunautaires',
            'languesProjets',
            'ressourcesFinancieres',
            'effectifs',
            'effectifsRedoublants',
            'effectifsAbandons',
            'effectifsHandicaps',
            'effectifsSituationsSpeciales',
            'cmg',
            'cfee',
            'entreeSixieme',
            'recrutementCi',
            'personnelEnseignant',
            'personnelAdministratif',
            'manuelsEleves',
            'manuelsMaitres',
            'dictionnaires',
            'materielDidactique',
            'capitalImmobilier',
            'capitalMobilier',
            'equipementInformatique',
            'geometrie',
            'commentaires'
        ]);
    }

    public function sheets(): array
    {
        return [
            new InformationsGeneralesSheet($this->rapport),
            new InfrastructuresSheet($this->rapport),
            new EffectifsSheet($this->rapport),
            new ExamensSheet($this->rapport),
            new PersonnelSheet($this->rapport),
            new MaterielPedagogiqueSheet($this->rapport),
            new EquipementsSheet($this->rapport),
            new ObservationsSheet($this->rapport),
        ];
    }
}

// ============ ONGLET 1 : INFORMATIONS GÉNÉRALES ============
class InformationsGeneralesSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        // Établissement
        $data->push(['ÉTABLISSEMENT']);
        $data->push(['Nom', $this->rapport->etablissement->etablissement ?? '']);
        $data->push(['Code', $this->rapport->etablissement->code ?? '']);
        $data->push(['Commune', $this->rapport->etablissement->commune ?? '']);
        $data->push(['Zone', $this->rapport->etablissement->zone ?? '']);
        $data->push(['']);
        
        // Directeur
        $info = $this->rapport->infoDirecteur;
        $data->push(['INFORMATIONS DU DIRECTEUR']);
        $data->push(['Prénom', $info->prenom ?? '']);
        $data->push(['Nom', $info->nom ?? '']);
        $data->push(['Téléphone', $info->telephone ?? '']);
        $data->push(['']);
        
        // Structures communautaires
        $struct = $this->rapport->structuresCommunautaires;
        if ($struct) {
            $data->push(['STRUCTURES COMMUNAUTAIRES']);
            $data->push(['CGE', $struct->cge ? 'Oui' : 'Non']);
            $data->push(['Président CGE', $struct->president_cge ?? '']);
            $data->push(['Téléphone Président', $struct->telephone_president ?? '']);
            $data->push(['']);
        }
        
        // Langues et projets
        $langues = $this->rapport->languesProjets;
        if ($langues) {
            $data->push(['LANGUES ET PROJETS']);
            $data->push(['Langue nationale enseignée', $langues->langue_nationale ?? '']);
            $data->push(['Classes bilangues', $langues->nombre_classes_bilangues ?? '']);
            $data->push(['Projets en cours', $langues->projets ?? '']);
            $data->push(['']);
        }
        
        // Ressources financières
        $finances = $this->rapport->ressourcesFinancieres;
        if ($finances) {
            $data->push(['RESSOURCES FINANCIÈRES']);
            $data->push(['Montant reçu (FCFA)', number_format($finances->montant_recu ?? 0, 0, ',', ' ')]);
            $data->push(['Montant dépensé (FCFA)', number_format($finances->montant_depense ?? 0, ',', ' ')]);
            $data->push(['Solde (FCFA)', number_format(($finances->montant_recu ?? 0) - ($finances->montant_depense ?? 0), 0, ',', ' ')]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            ['RAPPORT DE RENTRÉE - ' . $this->rapport->annee_scolaire],
            ['Généré le : ' . now()->format('d/m/Y à H:i')],
            [''],
        ];
    }

    public function title(): string
    {
        return '1. Informations Générales';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '10B981']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            2 => ['font' => ['italic' => true, 'size' => 9], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']]],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 40,
        ];
    }
}

// ============ ONGLET 2 : INFRASTRUCTURES ============
class InfrastructuresSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $infra = $this->rapport->infrastructures;
        
        return collect([
            ['SALLES DE CLASSE'],
            ['Salles en dur', $infra->salles_dur ?? 0],
            ['Salles en semi-dur', $infra->salles_semi_dur ?? 0],
            ['Salles en paille', $infra->salles_paille ?? 0],
            ['Total salles', ($infra->salles_dur ?? 0) + ($infra->salles_semi_dur ?? 0) + ($infra->salles_paille ?? 0)],
            [''],
            ['INSTALLATIONS'],
            ['Points d\'eau', $infra->points_eau ?? 0],
            ['Latrines fonctionnelles (Garçons)', $infra->latrines_garcons ?? 0],
            ['Latrines fonctionnelles (Filles)', $infra->latrines_filles ?? 0],
            ['Total latrines', ($infra->latrines_garcons ?? 0) + ($infra->latrines_filles ?? 0)],
            [''],
            ['AUTRES LOCAUX'],
            ['Bureau directeur', $infra->bureau_directeur ? 'Oui' : 'Non'],
            ['Magasin', $infra->magasin ? 'Oui' : 'Non'],
            ['Logement gardien', $infra->logement_gardien ? 'Oui' : 'Non'],
            ['Clôture', $infra->cloture ? 'Oui' : 'Non'],
        ]);
    }

    public function headings(): array
    {
        return [['INFRASTRUCTURES ET ÉQUIPEMENTS'], ['']];
    }

    public function title(): string
    {
        return '2. Infrastructures';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3B82F6']],
            ],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 35, 'B' => 20];
    }
}

// ============ ONGLET 3 : EFFECTIFS ============
class EffectifsSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        $data->push(['EFFECTIFS PAR CLASSE', '', '', '', '']);
        $data->push(['Classe', 'Garçons', 'Filles', 'Total', 'Ratio G/F']);
        
        $totalG = 0;
        $totalF = 0;
        
        foreach ($this->rapport->effectifs as $eff) {
            $total = ($eff->effectif_garcons ?? 0) + ($eff->effectif_filles ?? 0);
            $ratio = $eff->effectif_filles > 0 ? round(($eff->effectif_garcons ?? 0) / $eff->effectif_filles, 2) : 'N/A';
            
            $data->push([
                $eff->classe,
                $eff->effectif_garcons ?? 0,
                $eff->effectif_filles ?? 0,
                $total,
                $ratio
            ]);
            
            $totalG += $eff->effectif_garcons ?? 0;
            $totalF += $eff->effectif_filles ?? 0;
        }
        
        $data->push(['TOTAL', $totalG, $totalF, $totalG + $totalF, '']);
        $data->push(['']);
        
        // Redoublants
        $data->push(['REDOUBLANTS PAR CLASSE', '', '', '']);
        $data->push(['Classe', 'Garçons', 'Filles', 'Total']);
        foreach ($this->rapport->effectifsRedoublants as $red) {
            $data->push([
                $red->classe,
                $red->garcons ?? 0,
                $red->filles ?? 0,
                ($red->garcons ?? 0) + ($red->filles ?? 0)
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [['EFFECTIFS SCOLAIRES - ' . $this->rapport->annee_scolaire], ['']];
    }

    public function title(): string
    {
        return '3. Effectifs';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B5CF6']],
            ],
            3 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 15, 'B' => 12, 'C' => 12, 'D' => 12, 'E' => 12];
    }
}

// ============ ONGLET 4 : EXAMENS ET CONCOURS ============
class ExamensSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        // CMG
        $cmg = $this->rapport->cmg;
        if ($cmg) {
            $data->push(['CONCOURS DE MAÎTRISE DU CM2 (CMG)']);
            $data->push(['Candidats inscrits', ($cmg->inscrits_garcons ?? 0) + ($cmg->inscrits_filles ?? 0)]);
            $data->push(['  - Garçons', $cmg->inscrits_garcons ?? 0]);
            $data->push(['  - Filles', $cmg->inscrits_filles ?? 0]);
            $data->push(['Candidats présents', ($cmg->presents_garcons ?? 0) + ($cmg->presents_filles ?? 0)]);
            $data->push(['Admis', ($cmg->admis_garcons ?? 0) + ($cmg->admis_filles ?? 0)]);
            $taux = ($cmg->presents_garcons + $cmg->presents_filles) > 0 
                ? round((($cmg->admis_garcons + $cmg->admis_filles) / ($cmg->presents_garcons + $cmg->presents_filles)) * 100, 2) 
                : 0;
            $data->push(['Taux de réussite', $taux . ' %']);
            $data->push(['']);
        }
        
        // CFEE
        $cfee = $this->rapport->cfee;
        if ($cfee) {
            $data->push(['CERTIFICAT DE FIN D\'ÉTUDES ÉLÉMENTAIRES (CFEE)']);
            $data->push(['Candidats inscrits', ($cfee->inscrits_garcons ?? 0) + ($cfee->inscrits_filles ?? 0)]);
            $data->push(['  - Garçons', $cfee->inscrits_garcons ?? 0]);
            $data->push(['  - Filles', $cfee->inscrits_filles ?? 0]);
            $data->push(['Candidats présents', ($cfee->presents_garcons ?? 0) + ($cfee->presents_filles ?? 0)]);
            $data->push(['Admis', ($cfee->admis_garcons ?? 0) + ($cfee->admis_filles ?? 0)]);
            $taux = ($cfee->presents_garcons + $cfee->presents_filles) > 0 
                ? round((($cfee->admis_garcons + $cfee->admis_filles) / ($cfee->presents_garcons + $cfee->presents_filles)) * 100, 2) 
                : 0;
            $data->push(['Taux de réussite', $taux . ' %']);
            $data->push(['']);
        }
        
        // Entrée en 6ème
        $entree = $this->rapport->entreeSixieme;
        if ($entree) {
            $data->push(['ENTRÉE EN SIXIÈME']);
            $data->push(['Élèves orientés', ($entree->garcons ?? 0) + ($entree->filles ?? 0)]);
            $data->push(['  - Garçons', $entree->garcons ?? 0]);
            $data->push(['  - Filles', $entree->filles ?? 0]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [['EXAMENS ET CONCOURS'], ['']];
    }

    public function title(): string
    {
        return '4. Examens & Concours';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F59E0B']],
            ],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 35, 'B' => 20];
    }
}

// ============ ONGLET 5 : PERSONNEL ============
class PersonnelSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        $perso = $this->rapport->personnelEnseignant;
        if ($perso) {
            $data->push(['PERSONNEL ENSEIGNANT']);
            $data->push(['Total enseignants', $perso->total_personnel ?? 0]);
            $data->push(['  - Hommes', $perso->hommes ?? 0]);
            $data->push(['  - Femmes', $perso->femmes ?? 0]);
            $data->push(['']);
            $data->push(['Par statut']);
            $data->push(['  - Fonctionnaires', $perso->fonctionnaires ?? 0]);
            $data->push(['  - Contractuels', $perso->contractuels ?? 0]);
            $data->push(['  - Vacataires', $perso->vacataires ?? 0]);
            $data->push(['  - Bénévoles', $perso->benevoles ?? 0]);
            $data->push(['']);
        }
        
        $admin = $this->rapport->personnelAdministratif;
        if ($admin) {
            $data->push(['PERSONNEL ADMINISTRATIF']);
            $data->push(['Total personnel', $admin->total_personnel ?? 0]);
            $data->push(['  - Hommes', $admin->hommes ?? 0]);
            $data->push(['  - Femmes', $admin->femmes ?? 0]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [['PERSONNEL DE L\'ÉTABLISSEMENT'], ['']];
    }

    public function title(): string
    {
        return '5. Personnel';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EF4444']],
            ],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 35, 'B' => 20];
    }
}

// ============ ONGLET 6 : MATÉRIEL PÉDAGOGIQUE ============
class MaterielPedagogiqueSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        // Manuels élèves
        $data->push(['MANUELS SCOLAIRES ÉLÈVES', '', '', '']);
        $data->push(['Discipline', 'Classe', 'Nombre', 'État']);
        foreach ($this->rapport->manuelsEleves as $manuel) {
            $data->push([
                $manuel->discipline,
                $manuel->classe,
                $manuel->nombre ?? 0,
                $manuel->etat ?? ''
            ]);
        }
        $data->push(['']);
        
        // Guides du maître
        $data->push(['GUIDES DU MAÎTRE', '', '', '']);
        $data->push(['Discipline', 'Classe', 'Nombre', 'État']);
        foreach ($this->rapport->manuelsMaitres as $guide) {
            $data->push([
                $guide->discipline,
                $guide->classe,
                $guide->nombre ?? 0,
                $guide->etat ?? ''
            ]);
        }
        $data->push(['']);
        
        // Dictionnaires
        $dico = $this->rapport->dictionnaires;
        if ($dico) {
            $data->push(['DICTIONNAIRES']);
            $data->push(['Nombre de dictionnaires', $dico->nombre ?? 0]);
            $data->push(['État', $dico->etat ?? '']);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [['MATÉRIEL PÉDAGOGIQUE'], ['']];
    }

    public function title(): string
    {
        return '6. Matériel Pédagogique';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '06B6D4']],
            ],
            3 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 25, 'B' => 15, 'C' => 12, 'D' => 15];
    }
}

// ============ ONGLET 7 : ÉQUIPEMENTS ============
class EquipementsSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        // Capital mobilier
        $data->push(['MOBILIER SCOLAIRE', '', '']);
        $data->push(['Type', 'Nombre', 'État']);
        foreach ($this->rapport->capitalMobilier as $mob) {
            $data->push([
                $mob->type,
                $mob->nombre ?? 0,
                $mob->etat ?? ''
            ]);
        }
        $data->push(['']);
        
        // Équipement informatique
        $info = $this->rapport->equipementInformatique;
        if ($info) {
            $data->push(['ÉQUIPEMENT INFORMATIQUE']);
            $data->push(['Ordinateurs', $info->nombre_ordinateurs ?? 0]);
            $data->push(['Imprimantes', $info->nombre_imprimantes ?? 0]);
            $data->push(['Connexion Internet', $info->connexion_internet ? 'Oui' : 'Non']);
            $data->push(['']);
        }
        
        // Matériel de géométrie
        $data->push(['MATÉRIEL DE GÉOMÉTRIE', '', '']);
        $data->push(['Type', 'Nombre', 'État']);
        foreach ($this->rapport->geometrie as $geo) {
            $data->push([
                $geo->type,
                $geo->nombre ?? 0,
                $geo->etat ?? ''
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [['ÉQUIPEMENTS ET MOBILIER'], ['']];
    }

    public function title(): string
    {
        return '7. Équipements';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']],
            ],
            3 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 30, 'B' => 15, 'C' => 15];
    }
}

// ============ ONGLET 8 : OBSERVATIONS ============
class ObservationsSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        $data = collect();
        
        $comm = $this->rapport->commentaires;
        if ($comm) {
            $data->push(['OBSERVATIONS ET COMMENTAIRES']);
            $data->push(['']);
            $data->push(['Besoins prioritaires']);
            $data->push([$comm->besoins_prioritaires ?? 'Aucun']);
            $data->push(['']);
            $data->push(['Difficultés rencontrées']);
            $data->push([$comm->difficultes ?? 'Aucune']);
            $data->push(['']);
            $data->push(['Observations générales']);
            $data->push([$comm->observations ?? 'Aucune']);
        }
        
        $data->push(['']);
        $data->push(['STATUT DU RAPPORT']);
        $data->push(['État', ucfirst($this->rapport->statut)]);
        $data->push(['Date de soumission', $this->rapport->date_soumission ? $this->rapport->date_soumission->format('d/m/Y H:i') : '-']);
        
        if ($this->rapport->statut === 'validé') {
            $data->push(['Date de validation', $this->rapport->date_validation ? $this->rapport->date_validation->format('d/m/Y H:i') : '-']);
        } elseif ($this->rapport->statut === 'rejeté') {
            $data->push(['Date de rejet', $this->rapport->date_rejet ? $this->rapport->date_rejet->format('d/m/Y H:i') : '-']);
            $data->push(['Motif du rejet', $this->rapport->motif_rejet ?? '']);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [['OBSERVATIONS ET COMMENTAIRES'], ['']];
    }

    public function title(): string
    {
        return '8. Observations';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '64748B']],
            ],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 80];
    }
}
