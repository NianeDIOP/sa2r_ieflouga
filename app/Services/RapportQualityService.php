<?php

namespace App\Services;

use App\Models\Rapport;
use Illuminate\Support\Collection;

class RapportQualityService
{
    /**
     * Calcule le score de qualité global d'un rapport sur 100
     */
    public function calculerScoreGlobal(Rapport $rapport): array
    {
        $scoreCompletude = $this->calculerScoreCompletude($rapport);
        $scoreCoherence = $this->calculerScoreCoherence($rapport);
        $scorePrecision = $this->calculerScorePrecision($rapport);

        $scoreTotal = $scoreCompletude['score'] + $scoreCoherence['score'] + $scorePrecision['score'];

        return [
            'score_total' => round($scoreTotal, 1),
            'completude' => $scoreCompletude,
            'coherence' => $scoreCoherence,
            'precision' => $scorePrecision,
            'badge' => $this->getBadgeQualite($scoreTotal),
            'anomalies' => $this->detecterAnomalies($rapport),
        ];
    }

    /**
     * Calcule le score de complétude (40 points max)
     */
    private function calculerScoreCompletude(Rapport $rapport): array
    {
        $score = 0;
        $details = [];
        $max = 40;

        // Info directeur (4 pts)
        if ($rapport->infoDirecteur) {
            $pts = 0;
            if ($rapport->infoDirecteur->directeur_nom) $pts += 1.5;
            if ($rapport->infoDirecteur->directeur_contact_1) $pts += 1;
            if ($rapport->infoDirecteur->directeur_email) $pts += 0.5;
            if ($rapport->infoDirecteur->distance_siege !== null) $pts += 1;
            $score += $pts;
            $details['info_directeur'] = ['score' => round($pts, 1), 'max' => 4];
        } else {
            $details['info_directeur'] = ['score' => 0, 'max' => 4, 'manquant' => true];
        }

        // Effectifs (10 pts) - 10/6 = 1.666... on limite à 10 max
        $effectifs = $rapport->effectifs;
        $niveauxAttendus = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
        $niveauxRemplis = 0;
        
        foreach ($niveauxAttendus as $niveau) {
            $effectif = $effectifs->firstWhere('niveau', $niveau);
            if ($effectif && $effectif->effectif_total > 0) {
                $niveauxRemplis++;
            }
        }
        $ptsEffectifs = min(10, ($niveauxRemplis / count($niveauxAttendus)) * 10);
        $score += $ptsEffectifs;
        $details['effectifs'] = ['score' => round($ptsEffectifs, 1), 'max' => 10];

        // Personnel (4 pts)
        if ($rapport->personnelEnseignant) {
            $pts = 0;
            if ($rapport->personnelEnseignant->total_personnel > 0) $pts += 2;
            if ($rapport->personnelEnseignant->ratio_eleves_enseignant !== null) $pts += 1;
            if ($rapport->personnelEnseignant->taux_feminisation !== null) $pts += 1;
            $score += $pts;
            $details['personnel'] = ['score' => $pts, 'max' => 4];
        } else {
            $details['personnel'] = ['score' => 0, 'max' => 4, 'manquant' => true];
        }

        // Infrastructures de base (3 pts)
        if ($rapport->infrastructuresBase) {
            $pts = 3;
            $score += $pts;
            $details['infrastructures'] = ['score' => $pts, 'max' => 3];
        } else {
            $details['infrastructures'] = ['score' => 0, 'max' => 3, 'manquant' => true];
        }

        // Structures communautaires - APEE, CGE (2 pts)
        if ($rapport->structuresCommunautaires) {
            $pts = 2;
            $score += $pts;
            $details['structures_comm'] = ['score' => $pts, 'max' => 2];
        } else {
            $details['structures_comm'] = ['score' => 0, 'max' => 2, 'manquant' => true];
        }

        // Langues et Projets (2 pts)
        if ($rapport->languesProjets) {
            $pts = 2;
            $score += $pts;
            $details['langues_projets'] = ['score' => $pts, 'max' => 2];
        } else {
            $details['langues_projets'] = ['score' => 0, 'max' => 2, 'manquant' => true];
        }

        // Ressources financières (2 pts)
        if ($rapport->ressourcesFinancieres) {
            $pts = 2;
            $score += $pts;
            $details['finances'] = ['score' => $pts, 'max' => 2];
        } else {
            $details['finances'] = ['score' => 0, 'max' => 2, 'manquant' => true];
        }

        // Examens (4 pts) - 1 pt par examen
        $ptsExamens = 0;
        if ($rapport->cfee) $ptsExamens += 1;
        if ($rapport->cmg) $ptsExamens += 1;
        if ($rapport->recrutementCi) $ptsExamens += 1;
        if ($rapport->entreeSixieme) $ptsExamens += 1;
        $score += $ptsExamens;
        $details['examens'] = ['score' => $ptsExamens, 'max' => 4];

        // Capital immobilier - Bâtiments (2 pts)
        if ($rapport->capitalImmobilier) {
            $pts = 2;
            $score += $pts;
            $details['capital_immo'] = ['score' => $pts, 'max' => 2];
        } else {
            $details['capital_immo'] = ['score' => 0, 'max' => 2, 'manquant' => true];
        }

        // Capital mobilier - Tables/Bancs (1.5 pts au lieu de 2)
        if ($rapport->capitalMobilier) {
            $pts = 1.5;
            $score += $pts;
            $details['capital_mob'] = ['score' => $pts, 'max' => 1.5];
        } else {
            $details['capital_mob'] = ['score' => 0, 'max' => 1.5, 'manquant' => true];
        }

        // Matériel didactique (1.5 pts)
        if ($rapport->materielDidactique) {
            $pts = 1.5;
            $score += $pts;
            $details['materiel_didac'] = ['score' => $pts, 'max' => 1.5];
        } else {
            $details['materiel_didac'] = ['score' => 0, 'max' => 1.5, 'manquant' => true];
        }

        // Équipement informatique (1 pt au lieu de 1.5)
        if ($rapport->equipementInformatique) {
            $pts = 1;
            $score += $pts;
            $details['equip_info'] = ['score' => $pts, 'max' => 1];
        } else {
            $details['equip_info'] = ['score' => 0, 'max' => 1, 'manquant' => true];
        }

        // Manuels scolaires (2 pts)
        $ptsManuels = 0;
        if ($rapport->manuelsEleves->isNotEmpty()) $ptsManuels += 1;
        if ($rapport->manuelsMaitre->isNotEmpty()) $ptsManuels += 1;
        $score += $ptsManuels;
        $details['manuels'] = ['score' => $ptsManuels, 'max' => 2];

        // Dictionnaires (1 pt)
        if ($rapport->dictionnaires) {
            $pts = 1;
            $score += $pts;
            $details['dictionnaires'] = ['score' => $pts, 'max' => 1];
        } else {
            $details['dictionnaires'] = ['score' => 0, 'max' => 1, 'manquant' => true];
        }

        // S'assurer que le score ne dépasse jamais 40
        $score = min($score, $max);

        return [
            'score' => round($score, 1),
            'max' => $max,
            'pourcentage' => round(($score / $max) * 100, 1),
            'details' => $details,
        ];
    }

    /**
     * Calcule le score de cohérence (30 points max)
     */
    private function calculerScoreCoherence(Rapport $rapport): array
    {
        $score = 0;
        $erreurs = [];
        $max = 30;

        // Cohérence des totaux effectifs (10 pts)
        $erreursTotaux = 0;
        foreach ($rapport->effectifs as $effectif) {
            $totalCalcule = ($effectif->effectif_garcons ?? 0) + ($effectif->effectif_filles ?? 0);
            if ($effectif->effectif_total != $totalCalcule) {
                $erreursTotaux++;
                $erreurs[] = "Niveau {$effectif->niveau}: total incohérent ({$effectif->effectif_total} vs {$totalCalcule})";
            }

            // Redoublants <= Effectifs
            if (($effectif->redoublants_total ?? 0) > $effectif->effectif_total) {
                $erreursTotaux++;
                $erreurs[] = "Niveau {$effectif->niveau}: redoublants > effectifs";
            }

            // Abandons <= Effectifs
            if (($effectif->abandons_total ?? 0) > $effectif->effectif_total) {
                $erreursTotaux++;
                $erreurs[] = "Niveau {$effectif->niveau}: abandons > effectifs";
            }
        }
        $ptsTotaux = max(0, 10 - ($erreursTotaux * 2));
        $score += $ptsTotaux;

        // Cohérence personnel (10 pts)
        if ($rapport->personnelEnseignant) {
            $pers = $rapport->personnelEnseignant;
            $totalCalcule = ($pers->total_personnel_hommes ?? 0) + ($pers->total_personnel_femmes ?? 0);
            
            if ($pers->total_personnel != $totalCalcule) {
                $erreurs[] = "Personnel: total incohérent ({$pers->total_personnel} vs {$totalCalcule})";
                $score += 5;
            } else {
                $score += 10;
            }
        }

        // Ratios réalistes (10 pts)
        $ptsRatios = 10;
        
        if ($rapport->personnelEnseignant) {
            $ratio = $rapport->personnelEnseignant->ratio_eleves_enseignant;
            
            // Ratio élèves/enseignant entre 10 et 80
            if ($ratio !== null && ($ratio < 10 || $ratio > 80)) {
                $ptsRatios -= 3;
                $erreurs[] = "Ratio élèves/enseignant irréaliste: {$ratio}";
            }

            // Taux féminisation entre 0 et 100
            $taux = $rapport->personnelEnseignant->taux_feminisation;
            if ($taux !== null && ($taux < 0 || $taux > 100)) {
                $ptsRatios -= 3;
                $erreurs[] = "Taux féminisation invalide: {$taux}%";
            }
        }

        // Vérifier valeurs négatives
        foreach ($rapport->effectifs as $effectif) {
            if ($effectif->effectif_total < 0 || 
                ($effectif->effectif_garcons ?? 0) < 0 || 
                ($effectif->effectif_filles ?? 0) < 0) {
                $ptsRatios -= 2;
                $erreurs[] = "Valeurs négatives détectées dans les effectifs";
                break;
            }
        }

        $score += $ptsRatios;

        return [
            'score' => round($score, 1),
            'max' => $max,
            'pourcentage' => round(($score / $max) * 100, 1),
            'erreurs' => $erreurs,
        ];
    }

    /**
     * Calcule le score de précision (30 points max)
     */
    private function calculerScorePrecision(Rapport $rapport): array
    {
        $score = 0;
        $max = 30;

        // Contacts renseignés (5 pts)
        if ($rapport->infoDirecteur) {
            $pts = 0;
            if ($rapport->infoDirecteur->directeur_contact_1) $pts += 2;
            if ($rapport->infoDirecteur->directeur_contact_2) $pts += 1;
            if ($rapport->infoDirecteur->directeur_email) $pts += 2;
            $score += $pts;
        }

        // Données détaillées handicaps/orphelins (10 pts)
        $ptsDetails = 0;
        foreach ($rapport->effectifs as $effectif) {
            if (($effectif->handicap_moteur_total ?? 0) > 0 ||
                ($effectif->handicap_visuel_total ?? 0) > 0 ||
                ($effectif->handicap_sourd_muet_total ?? 0) > 0 ||
                ($effectif->handicap_deficience_intel_total ?? 0) > 0) {
                $ptsDetails += 1;
            }
            if (($effectif->orphelins_total ?? 0) > 0) {
                $ptsDetails += 0.5;
            }
        }
        $score += min(10, $ptsDetails);

        // Résultats examens (10 pts)
        $ptsExamens = 0;
        if ($rapport->cfee) $ptsExamens += 2.5;
        if ($rapport->cmg) $ptsExamens += 2.5;
        if ($rapport->recrutementCi) $ptsExamens += 2.5;
        if ($rapport->entreeSixieme) $ptsExamens += 2.5;
        $score += $ptsExamens;

        // Ressources financières (5 pts)
        if ($rapport->ressourcesFinancieres) {
            $score += 5;
        }

        return [
            'score' => round($score, 1),
            'max' => $max,
            'pourcentage' => round(($score / $max) * 100, 1),
        ];
    }

    /**
     * Détecte les anomalies et valeurs aberrantes
     */
    public function detecterAnomalies(Rapport $rapport): array
    {
        $anomalies = [];

        // Variation avec année précédente
        $rapportPrecedent = Rapport::where('etablissement_id', $rapport->etablissement_id)
            ->where('annee_scolaire', '!=', $rapport->annee_scolaire)
            ->where('statut', 'validé')
            ->orderBy('annee_scolaire', 'desc')
            ->first();

        if ($rapportPrecedent) {
            $effectifActuel = $rapport->effectifs->sum('effectif_total');
            $effectifPrecedent = $rapportPrecedent->effectifs->sum('effectif_total');

            if ($effectifPrecedent > 0) {
                $variation = abs($effectifActuel - $effectifPrecedent) / $effectifPrecedent * 100;
                
                if ($variation > 30) {
                    $anomalies[] = [
                        'type' => 'variation_importante',
                        'message' => "Variation de {$variation}% des effectifs par rapport à l'année précédente",
                        'gravite' => 'warning',
                    ];
                }
            }
        }

        // Classes sans élèves
        foreach ($rapport->effectifs as $effectif) {
            if ($effectif->nombre_classes > 0 && $effectif->effectif_total == 0) {
                $anomalies[] = [
                    'type' => 'classe_vide',
                    'message' => "Niveau {$effectif->niveau}: {$effectif->nombre_classes} classe(s) sans élèves",
                    'gravite' => 'error',
                ];
            }
        }

        // Ratio anormal élèves/enseignant
        if ($rapport->personnelEnseignant && $rapport->personnelEnseignant->ratio_eleves_enseignant) {
            $ratio = $rapport->personnelEnseignant->ratio_eleves_enseignant;
            if ($ratio > 60) {
                $anomalies[] = [
                    'type' => 'ratio_eleve_enseignant',
                    'message' => "Ratio élèves/enseignant très élevé: {$ratio}",
                    'gravite' => 'warning',
                ];
            } elseif ($ratio < 15) {
                $anomalies[] = [
                    'type' => 'ratio_eleve_enseignant',
                    'message' => "Ratio élèves/enseignant très faible: {$ratio}",
                    'gravite' => 'info',
                ];
            }
        }

        // Taux redoublement élevé
        foreach ($rapport->effectifs as $effectif) {
            if ($effectif->effectif_total > 0) {
                $tauxRedoublement = (($effectif->redoublants_total ?? 0) / $effectif->effectif_total) * 100;
                if ($tauxRedoublement > 20) {
                    $anomalies[] = [
                        'type' => 'redoublement_eleve',
                        'message' => "Niveau {$effectif->niveau}: taux de redoublement élevé ({$tauxRedoublement}%)",
                        'gravite' => 'warning',
                    ];
                }
            }
        }

        // Taux abandon élevé
        foreach ($rapport->effectifs as $effectif) {
            if ($effectif->effectif_total > 0) {
                $tauxAbandon = (($effectif->abandons_total ?? 0) / $effectif->effectif_total) * 100;
                if ($tauxAbandon > 10) {
                    $anomalies[] = [
                        'type' => 'abandon_eleve',
                        'message' => "Niveau {$effectif->niveau}: taux d'abandon élevé ({$tauxAbandon}%)",
                        'gravite' => 'error',
                    ];
                }
            }
        }

        return $anomalies;
    }

    /**
     * Retourne le badge de qualité
     */
    private function getBadgeQualite(float $score): array
    {
        if ($score >= 90) {
            return ['label' => 'Excellent', 'icon' => '⭐', 'color' => 'emerald'];
        } elseif ($score >= 80) {
            return ['label' => 'Très bien', 'icon' => '✅', 'color' => 'green'];
        } elseif ($score >= 70) {
            return ['label' => 'Bon', 'icon' => '👍', 'color' => 'blue'];
        } elseif ($score >= 60) {
            return ['label' => 'Acceptable', 'icon' => '⚠️', 'color' => 'amber'];
        } elseif ($score >= 50) {
            return ['label' => 'À améliorer', 'icon' => '🔧', 'color' => 'orange'];
        } else {
            return ['label' => 'Insuffisant', 'icon' => '❌', 'color' => 'red'];
        }
    }

    /**
     * Génère des recommandations pour améliorer la qualité
     */
    public function genererRecommandations(Rapport $rapport): array
    {
        $recommandations = [];
        $qualite = $this->calculerScoreGlobal($rapport);

        // Recommandations complétude - Détailler les sections manquantes
        if ($qualite['completude']['score'] < 30) {
            foreach ($qualite['completude']['details'] as $section => $detail) {
                if (isset($detail['manquant']) && $detail['manquant']) {
                    $labels = [
                        'info_directeur' => 'Informations du directeur',
                        'personnel' => 'Personnel enseignant',
                        'infrastructures' => 'Infrastructures de base',
                        'structures_comm' => 'Structures communautaires (APEE, CGE)',
                        'langues_projets' => 'Langues et projets',
                        'finances' => 'Ressources financières',
                        'capital_immo' => 'Capital immobilier (bâtiments)',
                        'capital_mob' => 'Capital mobilier (tables, bancs)',
                        'materiel_didac' => 'Matériel didactique',
                        'equip_info' => 'Équipement informatique',
                        'dictionnaires' => 'Dictionnaires',
                    ];
                    
                    $label = $labels[$section] ?? ucfirst(str_replace('_', ' ', $section));
                    $recommandations[] = [
                        'categorie' => 'completude',
                        'message' => "Compléter la section : {$label}",
                        'priorite' => 'haute',
                    ];
                }
            }
        }

        // Recommandations spécifiques pour effectifs incomplets
        if (isset($qualite['completude']['details']['effectifs'])) {
            $scoreEffectifs = $qualite['completude']['details']['effectifs']['score'];
            $maxEffectifs = $qualite['completude']['details']['effectifs']['max'];
            
            if ($scoreEffectifs < $maxEffectifs) {
                $recommandations[] = [
                    'categorie' => 'completude',
                    'message' => 'Renseigner tous les niveaux (CI, CP, CE1, CE2, CM1, CM2)',
                    'priorite' => 'haute',
                ];
            }
        }

        // Recommandations pour manuels incomplets
        if (isset($qualite['completude']['details']['manuels'])) {
            $scoreManuels = $qualite['completude']['details']['manuels']['score'];
            if ($scoreManuels < 2) {
                if ($rapport->manuelsEleves->isEmpty()) {
                    $recommandations[] = [
                        'categorie' => 'completude',
                        'message' => 'Ajouter les manuels des élèves',
                        'priorite' => 'moyenne',
                    ];
                }
                if ($rapport->manuelsMaitre->isEmpty()) {
                    $recommandations[] = [
                        'categorie' => 'completude',
                        'message' => 'Ajouter les manuels du maître',
                        'priorite' => 'moyenne',
                    ];
                }
            }
        }

        // Recommandations cohérence
        if (!empty($qualite['coherence']['erreurs'])) {
            foreach ($qualite['coherence']['erreurs'] as $erreur) {
                $recommandations[] = [
                    'categorie' => 'coherence',
                    'message' => "Corriger : " . $erreur,
                    'priorite' => 'haute',
                ];
            }
        }

        // Recommandations anomalies
        foreach ($qualite['anomalies'] as $anomalie) {
            if ($anomalie['gravite'] === 'error') {
                $recommandations[] = [
                    'categorie' => 'anomalie',
                    'message' => $anomalie['message'],
                    'priorite' => 'haute',
                ];
            } elseif ($anomalie['gravite'] === 'warning') {
                $recommandations[] = [
                    'categorie' => 'anomalie',
                    'message' => $anomalie['message'],
                    'priorite' => 'moyenne',
                ];
            }
        }

        return $recommandations;
    }

    /**
     * Statistiques globales de qualité pour tous les rapports
     */
    public function statistiquesGlobales(string $anneeScolaire = null): array
    {
        $query = Rapport::with([
            'etablissement',
            'effectifs',
            'personnelEnseignant',
            'infoDirecteur',
        ])->where('statut', 'validé');

        if ($anneeScolaire) {
            $query->where('annee_scolaire', $anneeScolaire);
        }

        $rapports = $query->get();
        $scores = [];

        foreach ($rapports as $rapport) {
            $scores[] = $this->calculerScoreGlobal($rapport);
        }

        $scoresTotal = collect($scores)->pluck('score_total');

        return [
            'total_rapports' => $rapports->count(),
            'score_moyen' => round($scoresTotal->avg(), 1),
            'score_median' => round($scoresTotal->median(), 1),
            'score_min' => round($scoresTotal->min(), 1),
            'score_max' => round($scoresTotal->max(), 1),
            'repartition' => [
                'excellent' => $scoresTotal->filter(fn($s) => $s >= 90)->count(),
                'tres_bien' => $scoresTotal->filter(fn($s) => $s >= 80 && $s < 90)->count(),
                'bon' => $scoresTotal->filter(fn($s) => $s >= 70 && $s < 80)->count(),
                'acceptable' => $scoresTotal->filter(fn($s) => $s >= 60 && $s < 70)->count(),
                'a_ameliorer' => $scoresTotal->filter(fn($s) => $s >= 50 && $s < 60)->count(),
                'insuffisant' => $scoresTotal->filter(fn($s) => $s < 50)->count(),
            ],
        ];
    }
}
