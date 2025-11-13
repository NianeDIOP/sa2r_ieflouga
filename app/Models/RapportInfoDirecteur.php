<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportInfoDirecteur extends Model
{
    protected $table = 'rapport_info_directeur';

    protected $fillable = [
        'rapport_id',
        'directeur_nom',
        'directeur_contact_1',
        'directeur_contact_2',
        'directeur_email',
        'distance_siege',
    ];

    protected $casts = [
        'distance_siege' => 'decimal:2',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
