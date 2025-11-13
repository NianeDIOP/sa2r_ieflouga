<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportMesure extends Model
{
    protected $table = 'rapport_mesure';

    protected $fillable = [
        'rapport_id',
        'decametres',
        'metres_plies',
        'centimetres',
        'reglets',
        'balances_plateaux',
        'balances_electroniques',
        'poids_masses',
        'recipients_gradues',
        'eprouvettes',
        'verres_doseurs',
        'chronometres',
        'horloges_demonstration',
        'sabliers',
        'etat_instruments_mesure',
        'besoins_mesure',
        'budget_estime_mesure',
        'observations_mesure'
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}