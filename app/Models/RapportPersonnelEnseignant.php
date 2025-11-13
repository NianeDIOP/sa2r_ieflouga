<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportPersonnelEnseignant extends Model
{
    protected $table = 'rapport_personnel_enseignant';

    protected $fillable = [
        'rapport_id',
        'titulaires_hommes',
        'titulaires_femmes',
        'titulaires_total',
        'vacataires_hommes',
        'vacataires_femmes',
        'vacataires_total',
        'volontaires_hommes',
        'volontaires_femmes',
        'volontaires_total',
        'contractuels_hommes',
        'contractuels_femmes',
        'contractuels_total',
        'communautaires_hommes',
        'communautaires_femmes',
        'communautaires_total',
        'instituteurs_hommes',
        'instituteurs_femmes',
        'instituteurs_total',
        'instituteurs_adjoints_hommes',
        'instituteurs_adjoints_femmes',
        'instituteurs_adjoints_total',
        'professeurs_hommes',
        'professeurs_femmes',
        'professeurs_total',
        'bac_hommes',
        'bac_femmes',
        'bac_total',
        'bfem_hommes',
        'bfem_femmes',
        'bfem_total',
        'cfee_hommes',
        'cfee_femmes',
        'cfee_total',
        'licence_hommes',
        'licence_femmes',
        'licence_total',
        'master_hommes',
        'master_femmes',
        'master_total',
        'autres_diplomes_hommes',
        'autres_diplomes_femmes',
        'autres_diplomes_total',
        'enseignants_formes_tic_hommes',
        'enseignants_formes_tic_femmes',
        'enseignants_formes_tic_total',
        'total_personnel_hommes',
        'total_personnel_femmes',
        'total_personnel',
        'ratio_eleves_enseignant',
        'taux_feminisation',
    ];

    protected $casts = [
        'ratio_eleves_enseignant' => 'decimal:2',
        'taux_feminisation' => 'decimal:2',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Calcul automatique de tous les totaux et ratios
     */
    public static function booted()
    {
        static::saving(function ($model) {
            // Totaux par spécialité
            $model->titulaires_total = ($model->titulaires_hommes ?? 0) + ($model->titulaires_femmes ?? 0);
            $model->vacataires_total = ($model->vacataires_hommes ?? 0) + ($model->vacataires_femmes ?? 0);
            $model->volontaires_total = ($model->volontaires_hommes ?? 0) + ($model->volontaires_femmes ?? 0);
            $model->contractuels_total = ($model->contractuels_hommes ?? 0) + ($model->contractuels_femmes ?? 0);
            $model->communautaires_total = ($model->communautaires_hommes ?? 0) + ($model->communautaires_femmes ?? 0);

            // Totaux par corps
            $model->instituteurs_total = ($model->instituteurs_hommes ?? 0) + ($model->instituteurs_femmes ?? 0);
            $model->instituteurs_adjoints_total = ($model->instituteurs_adjoints_hommes ?? 0) + ($model->instituteurs_adjoints_femmes ?? 0);
            $model->professeurs_total = ($model->professeurs_hommes ?? 0) + ($model->professeurs_femmes ?? 0);

            // Totaux par diplômes
            $model->bac_total = ($model->bac_hommes ?? 0) + ($model->bac_femmes ?? 0);
            $model->bfem_total = ($model->bfem_hommes ?? 0) + ($model->bfem_femmes ?? 0);
            $model->cfee_total = ($model->cfee_hommes ?? 0) + ($model->cfee_femmes ?? 0);
            $model->licence_total = ($model->licence_hommes ?? 0) + ($model->licence_femmes ?? 0);
            $model->master_total = ($model->master_hommes ?? 0) + ($model->master_femmes ?? 0);
            $model->autres_diplomes_total = ($model->autres_diplomes_hommes ?? 0) + ($model->autres_diplomes_femmes ?? 0);

            // TIC
            $model->enseignants_formes_tic_total = ($model->enseignants_formes_tic_hommes ?? 0) + ($model->enseignants_formes_tic_femmes ?? 0);

            // TOTAUX GÉNÉRAUX (basés sur spécialité pour éviter doublons)
            $model->total_personnel_hommes = 
                ($model->titulaires_hommes ?? 0) +
                ($model->vacataires_hommes ?? 0) +
                ($model->volontaires_hommes ?? 0) +
                ($model->contractuels_hommes ?? 0) +
                ($model->communautaires_hommes ?? 0);

            $model->total_personnel_femmes = 
                ($model->titulaires_femmes ?? 0) +
                ($model->vacataires_femmes ?? 0) +
                ($model->volontaires_femmes ?? 0) +
                ($model->contractuels_femmes ?? 0) +
                ($model->communautaires_femmes ?? 0);

            $model->total_personnel = $model->total_personnel_hommes + $model->total_personnel_femmes;

            // Taux de féminisation
            if ($model->total_personnel > 0) {
                $model->taux_feminisation = round(
                    ($model->total_personnel_femmes / $model->total_personnel) * 100,
                    2
                );
            } else {
                $model->taux_feminisation = 0;
            }

            // Ratio élèves/enseignant (sera calculé après si effectifs disponibles)
            // Pour l'instant on le laisse à 0, il sera mis à jour par un observer ou controller
        });
    }
}
