<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportGeometrie extends Model
{
    protected $table = 'rapport_geometrie';

    protected $fillable = [
        'rapport_id',
        'regle_plate_total',
        'regle_plate_bon_etat',
        'equerre_total',
        'equerre_bon_etat',
        'compas_total',
        'compas_bon_etat',
        'rapporteur_total',
        'rapporteur_bon_etat',
        'besoins_geometrie',
        'budget_estime_geometrie',
        'observations_geometrie'
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}