<?php

namespace App\Observers;

use App\Models\RapportPersonnelEnseignant;

class RapportPersonnelEnseignantObserver
{
    /**
     * Handle the RapportPersonnelEnseignant "creating" event.
     */
    public function creating(RapportPersonnelEnseignant $rapportPersonnelEnseignant): void
    {
        $this->calculateTotalsAndRatios($rapportPersonnelEnseignant);
    }

    /**
     * Handle the RapportPersonnelEnseignant "updating" event.
     */
    public function updating(RapportPersonnelEnseignant $rapportPersonnelEnseignant): void
    {
        $this->calculateTotalsAndRatios($rapportPersonnelEnseignant);
    }

    /**
     * Calculate all totals and ratios automatically
     */
    private function calculateTotalsAndRatios(RapportPersonnelEnseignant $rapport): void
    {
        // Calculate individual category totals
        
        // SPÉCIALITÉ
        $rapport->titulaires_total = ($rapport->titulaires_hommes ?? 0) + ($rapport->titulaires_femmes ?? 0);
        $rapport->vacataires_total = ($rapport->vacataires_hommes ?? 0) + ($rapport->vacataires_femmes ?? 0);
        $rapport->volontaires_total = ($rapport->volontaires_hommes ?? 0) + ($rapport->volontaires_femmes ?? 0);
        $rapport->contractuels_total = ($rapport->contractuels_hommes ?? 0) + ($rapport->contractuels_femmes ?? 0);
        $rapport->communautaires_total = ($rapport->communautaires_hommes ?? 0) + ($rapport->communautaires_femmes ?? 0);
        
        // CORPS
        $rapport->instituteurs_total = ($rapport->instituteurs_hommes ?? 0) + ($rapport->instituteurs_femmes ?? 0);
        $rapport->instituteurs_adjoints_total = ($rapport->instituteurs_adjoints_hommes ?? 0) + ($rapport->instituteurs_adjoints_femmes ?? 0);
        $rapport->professeurs_total = ($rapport->professeurs_hommes ?? 0) + ($rapport->professeurs_femmes ?? 0);
        
        // DIPLÔMES
        $rapport->bac_total = ($rapport->bac_hommes ?? 0) + ($rapport->bac_femmes ?? 0);
        $rapport->bfem_total = ($rapport->bfem_hommes ?? 0) + ($rapport->bfem_femmes ?? 0);
        $rapport->cfee_total = ($rapport->cfee_hommes ?? 0) + ($rapport->cfee_femmes ?? 0);
        $rapport->licence_total = ($rapport->licence_hommes ?? 0) + ($rapport->licence_femmes ?? 0);
        $rapport->master_total = ($rapport->master_hommes ?? 0) + ($rapport->master_femmes ?? 0);
        $rapport->autres_diplomes_total = ($rapport->autres_diplomes_hommes ?? 0) + ($rapport->autres_diplomes_femmes ?? 0);
        
        // TIC
        $rapport->enseignants_formes_tic_total = ($rapport->enseignants_formes_tic_hommes ?? 0) + ($rapport->enseignants_formes_tic_femmes ?? 0);
        
        // TOTAUX GÉNÉRAUX (basés sur spécialité uniquement pour éviter double comptage)
        $rapport->total_personnel_hommes = 
            ($rapport->titulaires_hommes ?? 0) +
            ($rapport->vacataires_hommes ?? 0) +
            ($rapport->volontaires_hommes ?? 0) +
            ($rapport->contractuels_hommes ?? 0) +
            ($rapport->communautaires_hommes ?? 0);
            
        $rapport->total_personnel_femmes = 
            ($rapport->titulaires_femmes ?? 0) +
            ($rapport->vacataires_femmes ?? 0) +
            ($rapport->volontaires_femmes ?? 0) +
            ($rapport->contractuels_femmes ?? 0) +
            ($rapport->communautaires_femmes ?? 0);
            
        $rapport->total_personnel = $rapport->total_personnel_hommes + $rapport->total_personnel_femmes;
        
        // TAUX DE FÉMINISATION
        $rapport->taux_feminisation = $rapport->total_personnel > 0 
            ? round(($rapport->total_personnel_femmes / $rapport->total_personnel) * 100, 2)
            : 0;
        
        // RATIO ÉLÈVES/ENSEIGNANT (nécessite les données d'effectifs)
        // Pour l'instant on le laisse à 0, il sera calculé quand on aura accès aux effectifs
        $rapport->ratio_eleves_enseignant = 0;
    }
}
