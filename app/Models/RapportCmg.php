<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportCmg extends Model
{
    protected $table = 'rapport_cmg';

    protected $fillable = [
        'rapport_id',
        'cmg_nombre',
        'cmg_combinaison_1',
        'cmg_combinaison_2',
        'cmg_combinaison_3',
        'cmg_combinaison_autres',
    ];

    protected $casts = [
        'cmg_nombre' => 'integer',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
