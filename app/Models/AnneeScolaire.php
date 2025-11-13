<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    protected $table = 'annees_scolaires';
    
    protected $fillable = [
        'annee',
        'date_debut',
        'date_fin',
        'is_active',
        'description'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Scope pour récupérer l'année active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Activer cette année scolaire (et désactiver les autres)
     */
    public function activate()
    {
        // Désactiver toutes les années
        self::query()->update(['is_active' => false]);
        
        // Activer celle-ci
        $this->is_active = true;
        $this->save();
    }

    /**
     * Vérifier si l'année est active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Obtenir l'année scolaire active
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
