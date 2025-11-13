<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportEquipementInformatique extends Model
{
    protected $table = 'rapport_equipement_informatique';

    protected $fillable = [
        'rapport_id',
        'ordinateurs_fixes_total', 'ordinateurs_fixes_bon_etat',
        'ordinateurs_portables_total', 'ordinateurs_portables_bon_etat',
        'tablettes_total', 'tablettes_bon_etat',
        'imprimantes_laser_total', 'imprimantes_laser_bon_etat',
        'imprimantes_jet_encre_total', 'imprimantes_jet_encre_bon_etat',
        'imprimantes_multifonction_total', 'imprimantes_multifonction_bon_etat',
        'photocopieuses_total', 'photocopieuses_bon_etat',
        'videoprojecteurs_total', 'videoprojecteurs_bon_etat',
        'autres_equipements_total', 'autres_equipements_bon_etat',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }
}
