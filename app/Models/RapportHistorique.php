<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportHistorique extends Model
{
    protected $table = 'rapport_historique';

    protected $fillable = [
        'rapport_id',
        'user_id',
        'action',
        'commentaire',
        'ancien_statut',
        'nouveau_statut',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
