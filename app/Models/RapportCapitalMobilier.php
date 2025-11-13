<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportCapitalMobilier extends Model
{
    protected $table = 'rapport_capital_mobilier';

    protected $fillable = [
        'rapport_id',
        // Mobilier Élèves
        'tables_bancs_total', 
        'tables_bancs_bon_etat',
        'chaises_eleves_total',
        'chaises_eleves_bon_etat',
        'tables_individuelles_total',
        'tables_individuelles_bon_etat',
        // Mobilier Enseignants
        'bureaux_maitre_total',
        'bureaux_maitre_bon_etat',
        'chaises_maitre_total',
        'chaises_maitre_bon_etat',
        'tableaux_total',
        'tableaux_bon_etat',
        'armoires_total',
        'armoires_bon_etat',
        // Mobilier Administratif
        'bureaux_admin_total',
        'bureaux_admin_bon_etat',
        'chaises_admin_total',
        'chaises_admin_bon_etat',
        // Anciens champs (à conserver pour compatibilité)
        'bureaux_total', 
        'bureaux_bon_etat',
        'chaises_total', 
        'chaises_bon_etat',
        'tableaux_mobiles_total', 
        'tableaux_mobiles_bon_etat',
        'tableaux_interactifs_total', 
        'tableaux_interactifs_bon_etat',
        'tableaux_muraux_total', 
        'tableaux_muraux_bon_etat',
        'mat_drapeau_total', 
        'mat_drapeau_bon_etat',
        'drapeau_total', 
        'drapeau_bon_etat',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
