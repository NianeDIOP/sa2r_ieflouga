<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportCfee extends Model
{
    protected $table = 'rapport_cfee';

    protected $fillable = [
        'rapport_id',
        'cfee_candidats_total',
        'cfee_candidats_filles',
        'cfee_admis_total',
        'cfee_admis_filles',
        'cfee_taux_reussite',
        'cfee_taux_reussite_filles',
    ];

    protected $casts = [
        'cfee_candidats_total' => 'integer',
        'cfee_candidats_filles' => 'integer',
        'cfee_admis_total' => 'integer',
        'cfee_admis_filles' => 'integer',
        'cfee_taux_reussite' => 'decimal:2',
        'cfee_taux_reussite_filles' => 'decimal:2',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Calcul automatique des taux de réussite
     */
    public static function booted()
    {
        static::saving(function ($model) {
            // Taux de réussite global
            if ($model->cfee_candidats_total > 0) {
                $model->cfee_taux_reussite = round(
                    ($model->cfee_admis_total / $model->cfee_candidats_total) * 100,
                    2
                );
            } else {
                $model->cfee_taux_reussite = 0;
            }

            // Taux de réussite filles
            if ($model->cfee_candidats_filles > 0) {
                $model->cfee_taux_reussite_filles = round(
                    ($model->cfee_admis_filles / $model->cfee_candidats_filles) * 100,
                    2
                );
            } else {
                $model->cfee_taux_reussite_filles = 0;
            }
        });
    }
}
