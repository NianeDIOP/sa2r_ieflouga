<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportLanguesProjets extends Model
{
    protected $table = 'rapport_langues_projets';

    protected $fillable = [
        'rapport_id',
        'langue_nationale',
        'projets_informatiques_existe',
        'projets_informatiques_nom',
    ];

    protected $casts = [
        'projets_informatiques_existe' => 'boolean',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
