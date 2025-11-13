<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapportRessourcesFinancieres extends Model
{
    protected $table = 'rapport_ressources_financieres';

    protected $fillable = [
        'rapport_id',
        'subvention_etat_existe',
        'subvention_etat_montant',
        'subvention_partenaires_existe',
        'subvention_partenaires_montant',
        'subvention_collectivites_existe',
        'subvention_collectivites_montant',
        'subvention_communaute_existe',
        'subvention_communaute_montant',
        'ressources_generees_existe',
        'ressources_generees_montant',
        'total_ressources',
    ];

    protected $casts = [
        'subvention_etat_existe' => 'boolean',
        'subvention_etat_montant' => 'decimal:2',
        'subvention_partenaires_existe' => 'boolean',
        'subvention_partenaires_montant' => 'decimal:2',
        'subvention_collectivites_existe' => 'boolean',
        'subvention_collectivites_montant' => 'decimal:2',
        'subvention_communaute_existe' => 'boolean',
        'subvention_communaute_montant' => 'decimal:2',
        'ressources_generees_existe' => 'boolean',
        'ressources_generees_montant' => 'decimal:2',
        'total_ressources' => 'decimal:2',
    ];

    public function rapport(): BelongsTo
    {
        return $this->belongsTo(Rapport::class);
    }

    /**
     * Calcul automatique du total
     */
    public static function booted()
    {
        static::saving(function ($model) {
            $model->total_ressources = 
                ($model->subvention_etat_montant ?? 0) +
                ($model->subvention_partenaires_montant ?? 0) +
                ($model->subvention_collectivites_montant ?? 0) +
                ($model->subvention_communaute_montant ?? 0) +
                ($model->ressources_generees_montant ?? 0);
        });
    }
}
