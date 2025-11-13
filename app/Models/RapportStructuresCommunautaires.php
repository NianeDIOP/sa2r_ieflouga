<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportStructuresCommunautaires extends Model
{
    protected $table = 'rapport_structures_communautaires';

    protected $fillable = [
        'rapport_id',
        'cge_existe',
        'cge_hommes',
        'cge_femmes',
        'cge_total',
        'cge_president_nom',
        'cge_president_contact',
        'cge_tresorier_nom',
        'cge_tresorier_contact',
        'gscol_existe',
        'gscol_garcons',
        'gscol_filles',
        'gscol_total',
        'gscol_president_nom',
        'gscol_president_contact',
        'ape_existe',
        'ape_hommes',
        'ape_femmes',
        'ape_total',
        'ape_president_nom',
        'ape_president_contact',
        'ame_existe',
        'ame_nombre',
        'ame_president_nom',
        'ame_president_contact',
    ];

    protected $casts = [
        'cge_existe' => 'boolean',
        'cge_hommes' => 'integer',
        'cge_femmes' => 'integer',
        'cge_total' => 'integer',
        'gscol_existe' => 'boolean',
        'gscol_garcons' => 'integer',
        'gscol_filles' => 'integer',
        'gscol_total' => 'integer',
        'ape_existe' => 'boolean',
        'ape_hommes' => 'integer',
        'ape_femmes' => 'integer',
        'ape_total' => 'integer',
        'ame_existe' => 'boolean',
        'ame_nombre' => 'integer',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Calcul automatique des totaux
     */
    public static function booted()
    {
        static::saving(function ($model) {
            $model->cge_total = ($model->cge_hommes ?? 0) + ($model->cge_femmes ?? 0);
            $model->gscol_total = ($model->gscol_garcons ?? 0) + ($model->gscol_filles ?? 0);
            $model->ape_total = ($model->ape_hommes ?? 0) + ($model->ape_femmes ?? 0);
        });
    }
}
