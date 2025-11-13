<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportInfrastructuresBase extends Model
{
    protected $table = 'rapport_infrastructures_base';

    protected $fillable = [
        'rapport_id',
        'cpe_existe',
        'cpe_nombre',
        'cloture_existe',
        'cloture_type',
        'eau_existe',
        'eau_type',
        'electricite_existe',
        'electricite_type',
        'connexion_internet_existe',
        'connexion_internet_type',
        'cantine_existe',
        'cantine_type',
    ];

    protected $casts = [
        'cpe_existe' => 'boolean',
        'cpe_nombre' => 'integer',
        'cloture_existe' => 'boolean',
        'eau_existe' => 'boolean',
        'electricite_existe' => 'boolean',
        'connexion_internet_existe' => 'boolean',
        'cantine_existe' => 'boolean',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
