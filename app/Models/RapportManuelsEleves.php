<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportManuelsEleves extends Model
{
    protected $table = 'rapport_manuels_eleves';

    protected $fillable = [
        'rapport_id',
        'niveau',
        'lc_francais',
        'mathematiques',
        'edd',
        'dm',
        'manuel_classe',
        'livret_maison',
        'livret_devoir_gradue',
        'planche_alphabetique',
        'manuel_arabe',
        'manuel_religion',
        'autre_religion',
        'autres_manuels',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
