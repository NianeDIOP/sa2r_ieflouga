<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportManuelsMaitre extends Model
{
    protected $table = 'rapport_manuels_maitre';

    protected $fillable = [
        'rapport_id',
        'niveau',
        'guide_lc_francais',
        'guide_mathematiques',
        'guide_edd',
        'guide_dm',
        'guide_pedagogique',
        'guide_arabe_religieux',
        'guide_langue_nationale',
        'cahier_recits',
        'autres_manuels_maitre',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
