<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Etablissement extends Model
{
    protected $fillable = [
        'etablissement',
        'arrondissement',
        'commune',
        'district',
        'zone',
        'code',
        'geo_ref_x',
        'geo_ref_y',
        'statut',
        'type_statut',
        'date_creation',
        'date_ouverture'
    ];

    /**
     * Créer le compte utilisateur associé à l'établissement
     */
    public function createUserAccount()
    {
        // Vérifier si l'utilisateur existe déjà
        $existingUser = \App\Models\User::where('code', $this->code)->first();
        
        if (!$existingUser) {
            \App\Models\User::create([
                'name' => $this->etablissement,
                'code' => $this->code,
                'password' => Hash::make('sa2r2025'),
                'type' => 'etablissement',
                'is_active' => true
            ]);
        }
    }

    /**
     * Obtenir l'utilisateur associé
     */
    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'code', 'code');
    }

    /**
     * Obtenir tous les rapports de l'établissement
     */
    public function rapports()
    {
        return $this->hasMany(\App\Models\Rapport::class, 'etablissement_id');
    }
}
