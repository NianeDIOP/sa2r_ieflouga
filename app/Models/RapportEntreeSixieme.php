<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportEntreeSixieme extends Model
{
    protected $table = 'rapport_entree_sixieme';

    protected $fillable = [
        'rapport_id',
        'sixieme_candidats_total',
        'sixieme_candidats_filles',
        'sixieme_admis_total',
        'sixieme_admis_filles',
        'sixieme_taux_admission',
        'sixieme_taux_admission_filles',
    ];

    protected $casts = [
        'sixieme_candidats_total' => 'integer',
        'sixieme_candidats_filles' => 'integer',
        'sixieme_admis_total' => 'integer',
        'sixieme_admis_filles' => 'integer',
        'sixieme_taux_admission' => 'decimal:2',
        'sixieme_taux_admission_filles' => 'decimal:2',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Calcul automatique des taux d'admission
     */
    public static function booted()
    {
        static::saving(function ($model) {
            // Taux d'admission global
            if ($model->sixieme_candidats_total > 0) {
                $model->sixieme_taux_admission = round(
                    ($model->sixieme_admis_total / $model->sixieme_candidats_total) * 100,
                    2
                );
            } else {
                $model->sixieme_taux_admission = 0;
            }

            // Taux d'admission filles
            if ($model->sixieme_candidats_filles > 0) {
                $model->sixieme_taux_admission_filles = round(
                    ($model->sixieme_admis_filles / $model->sixieme_candidats_filles) * 100,
                    2
                );
            } else {
                $model->sixieme_taux_admission_filles = 0;
            }
        });
    }
}
