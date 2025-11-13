<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportMaterielDidactique extends Model
{
    protected $table = 'rapport_materiel_didactique';

    protected $fillable = [
        'rapport_id',
        'dico_francais_total', 'dico_francais_bon_etat',
        'dico_arabe_total', 'dico_arabe_bon_etat',
        'dico_autre_total', 'dico_autre_bon_etat',
        'regle_plate_total', 'regle_plate_bon_etat',
        'equerre_total', 'equerre_bon_etat',
        'compas_total', 'compas_bon_etat',
        'rapporteur_total', 'rapporteur_bon_etat',
        'decametre_total', 'decametre_bon_etat',
        'chaine_arpenteur_total', 'chaine_arpenteur_bon_etat',
        'boussole_total', 'boussole_bon_etat',
        'thermometre_total', 'thermometre_bon_etat',
        'kit_capacite_total', 'kit_capacite_bon_etat',
        'balance_total', 'balance_bon_etat',
        'globe_total', 'globe_bon_etat',
        'cartes_murales_total', 'cartes_murales_bon_etat',
        'planches_illustrees_total', 'planches_illustrees_bon_etat',
        'kit_materiel_scientifique_total', 'kit_materiel_scientifique_bon_etat',
        'autres_materiel_total', 'autres_materiel_bon_etat',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
