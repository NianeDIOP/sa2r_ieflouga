<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rapport extends Model
{
    protected $fillable = [
        'etablissement_id',
        'annee_scolaire',
        'trimestre',
        'date_rapport',
        'statut',
        'submitted_by',
        'submitted_at',
        'validated_by',
        'validated_at',
        'commentaire_validation',
        'date_soumission',
        'date_validation',
        'date_rejet',
        'motif_rejet',
        'commentaire_admin',
    ];

    protected $casts = [
        'date_rapport' => 'date',
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
        'date_soumission' => 'datetime',
        'date_validation' => 'datetime',
        'date_rejet' => 'datetime',
    ];

    /**
     * Relation : Un rapport appartient à un établissement
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Relation : Un rapport est soumis par un utilisateur
     */
    public function soumis_par(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Relation : Un rapport est validé par un utilisateur
     */
    public function valide_par(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Relation : Un rapport a plusieurs effectifs (6 niveaux)
     */
    public function effectifs(): HasMany
    {
        return $this->hasMany(RapportEffectifsClasse::class);
    }

    /**
     * Relation : Un rapport a un historique
     */
    public function historique(): HasMany
    {
        return $this->hasMany(RapportHistorique::class);
    }

    /**
     * Relation : Un rapport a des commentaires
     */
    public function commentaires(): HasMany
    {
        return $this->hasMany(RapportCommentaire::class);
    }

    /**
     * SECTION 1 : Informations Générales et Financières
     */
    public function infoDirecteur()
    {
        return $this->hasOne(RapportInfoDirecteur::class);
    }

    public function infrastructuresBase()
    {
        return $this->hasOne(RapportInfrastructuresBase::class);
    }

    public function structuresCommunautaires()
    {
        return $this->hasOne(RapportStructuresCommunautaires::class);
    }

    public function languesProjets()
    {
        return $this->hasOne(RapportLanguesProjets::class);
    }

    public function ressourcesFinancieres()
    {
        return $this->hasOne(RapportRessourcesFinancieres::class);
    }

    /**
     * SECTION 3 : Examens et Recrutement
     */
    public function recrutementCi()
    {
        return $this->hasOne(RapportRecrutementCi::class);
    }

    public function cmg()
    {
        return $this->hasOne(RapportCmg::class);
    }

    public function cfee()
    {
        return $this->hasOne(RapportCfee::class);
    }

    public function entreeSixieme()
    {
        return $this->hasOne(RapportEntreeSixieme::class);
    }

    /**
     * SECTION 4 : Personnel Enseignant
     */
    public function personnelEnseignant()
    {
        return $this->hasOne(RapportPersonnelEnseignant::class);
    }

    /**
     * SECTION 5 : Matériel et Mobilier
     */
    public function capitalImmobilier()
    {
        return $this->hasOne(RapportCapitalImmobilier::class);
    }

    public function capitalMobilier()
    {
        return $this->hasOne(RapportCapitalMobilier::class);
    }

    public function materielDidactique()
    {
        return $this->hasOne(RapportMaterielDidactique::class);
    }

    public function equipementInformatique()
    {
        return $this->hasOne(RapportEquipementInformatique::class);
    }

    public function manuelsEleves()
    {
        return $this->hasMany(RapportManuelsEleves::class);
    }

    public function manuelsMaitre()
    {
        return $this->hasMany(RapportManuelsMaitre::class);
    }

    public function dictionnaires()
    {
        return $this->hasOne(RapportDictionnaires::class);
    }

    /**
     * Scope : Rapports d'une année scolaire
     */
    public function scopeAnnee($query, $annee)
    {
        return $query->where('annee_scolaire', $annee);
    }

    /**
     * Scope : Rapports par statut
     */
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope : Rapports d'un établissement
     */
    public function scopeEtablissement($query, $etablissement_id)
    {
        return $query->where('etablissement_id', $etablissement_id);
    }

    /**
     * Vérifier si le rapport est modifiable
     */
    public function estModifiable(): bool
    {
        return in_array($this->statut, ['brouillon', 'rejeté']);
    }

    /**
     * Vérifier si le rapport est validé
     */
    public function estValide(): bool
    {
        return $this->statut === 'validé';
    }
}
