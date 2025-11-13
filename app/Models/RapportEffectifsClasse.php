<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportEffectifsClasse extends Model
{
    protected $table = 'rapport_effectifs_classe';

    protected $fillable = [
        'rapport_id',
        'niveau',
        'nombre_classes',
        'effectif_garcons',
        'effectif_filles',
        'effectif_total',
        'redoublants_garcons',
        'redoublants_filles',
        'redoublants_total',
        'abandons_garcons',
        'abandons_filles',
        'abandons_total',
        'handicap_moteur_garcons',
        'handicap_moteur_filles',
        'handicap_moteur_total',
        'handicap_visuel_garcons',
        'handicap_visuel_filles',
        'handicap_visuel_total',
        'handicap_sourd_muet_garcons',
        'handicap_sourd_muet_filles',
        'handicap_sourd_muet_total',
        'handicap_deficience_intel_garcons',
        'handicap_deficience_intel_filles',
        'handicap_deficience_intel_total',
        'orphelins_garcons',
        'orphelins_filles',
        'orphelins_total',
        'sans_extrait_garcons',
        'sans_extrait_filles',
        'sans_extrait_total',
    ];

    protected $casts = [
        'nombre_classes' => 'integer',
        'effectif_garcons' => 'integer',
        'effectif_filles' => 'integer',
        'effectif_total' => 'integer',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Keep totals consistent when saving.
     */
    public static function booted()
    {
        static::saving(function ($model) {
            // Recompute simple totals if not provided
            $model->effectif_total = ($model->effectif_garcons ?? 0) + ($model->effectif_filles ?? 0);
            $model->redoublants_total = ($model->redoublants_garcons ?? 0) + ($model->redoublants_filles ?? 0);
            $model->abandons_total = ($model->abandons_garcons ?? 0) + ($model->abandons_filles ?? 0);
            $model->handicap_moteur_total = ($model->handicap_moteur_garcons ?? 0) + ($model->handicap_moteur_filles ?? 0);
            $model->handicap_visuel_total = ($model->handicap_visuel_garcons ?? 0) + ($model->handicap_visuel_filles ?? 0);
            $model->handicap_sourd_muet_total = ($model->handicap_sourd_muet_garcons ?? 0) + ($model->handicap_sourd_muet_filles ?? 0);
            $model->handicap_deficience_intel_total = ($model->handicap_deficience_intel_garcons ?? 0) + ($model->handicap_deficience_intel_filles ?? 0);
            $model->orphelins_total = ($model->orphelins_garcons ?? 0) + ($model->orphelins_filles ?? 0);
            $model->sans_extrait_total = ($model->sans_extrait_garcons ?? 0) + ($model->sans_extrait_filles ?? 0);
        });
    }
}
