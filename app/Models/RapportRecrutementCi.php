<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportRecrutementCi extends Model
{
    protected $table = 'rapport_recrutement_ci';

    protected $fillable = [
        'rapport_id',
        'ci_nombre',
        'ci_combinaison_1',
        'ci_combinaison_2',
        'ci_combinaison_3',
        'ci_combinaison_autres',
        'ci_octobre_garcons',
        'ci_octobre_filles',
        'ci_octobre_total',
        'ci_janvier_garcons',
        'ci_janvier_filles',
        'ci_janvier_total',
        'ci_mai_garcons',
        'ci_mai_filles',
        'ci_mai_total',
        'ci_statut',
    ];

    protected $casts = [
        'ci_nombre' => 'integer',
        'ci_octobre_garcons' => 'integer',
        'ci_octobre_filles' => 'integer',
        'ci_octobre_total' => 'integer',
        'ci_janvier_garcons' => 'integer',
        'ci_janvier_filles' => 'integer',
        'ci_janvier_total' => 'integer',
        'ci_mai_garcons' => 'integer',
        'ci_mai_filles' => 'integer',
        'ci_mai_total' => 'integer',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Calcul automatique des totaux par période
     */
    public static function booted()
    {
        static::saving(function ($model) {
            $model->ci_octobre_total = ($model->ci_octobre_garcons ?? 0) + ($model->ci_octobre_filles ?? 0);
            $model->ci_janvier_total = ($model->ci_janvier_garcons ?? 0) + ($model->ci_janvier_filles ?? 0);
            $model->ci_mai_total = ($model->ci_mai_garcons ?? 0) + ($model->ci_mai_filles ?? 0);
        });
    }
}
