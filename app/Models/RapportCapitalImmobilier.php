<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportCapitalImmobilier extends Model
{
    protected $table = 'rapport_capital_immobilier';

    protected $fillable = [
        'rapport_id',
        'salles_dur_total', 'salles_dur_bon_etat',
        'abris_provisoires_total', 'abris_provisoires_bon_etat',
        'bloc_admin_total', 'bloc_admin_bon_etat',
        'magasin_total', 'magasin_bon_etat',
        'salle_informatique_total', 'salle_informatique_bon_etat',
        'salle_bibliotheque_total', 'salle_bibliotheque_bon_etat',
        'cuisine_total', 'cuisine_bon_etat',
        'refectoire_total', 'refectoire_bon_etat',
        'toilettes_enseignants_total', 'toilettes_enseignants_bon_etat',
        'toilettes_garcons_total', 'toilettes_garcons_bon_etat',
        'toilettes_filles_total', 'toilettes_filles_bon_etat',
        'toilettes_mixtes_total', 'toilettes_mixtes_bon_etat',
        'logement_directeur_total', 'logement_directeur_bon_etat',
        'logement_gardien_total', 'logement_gardien_bon_etat',
        'autres_infrastructures_total', 'autres_infrastructures_bon_etat',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
