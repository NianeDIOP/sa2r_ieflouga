<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportDictionnaires extends Model
{
    protected $table = 'rapport_dictionnaires';

    protected $fillable = [
        'rapport_id',
        'dico_francais_total',
        'dico_francais_bon_etat',
        'dico_arabe_total',
        'dico_arabe_bon_etat',
        'dico_autre_total',
        'dico_autre_bon_etat',
        'besoins_dictionnaires',
        'budget_estime_dictionnaires',
        'observations_dictionnaires'
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}