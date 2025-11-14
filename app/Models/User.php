<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type',
        'email',
        'password',
        'is_active',
        'etablissement_id',
        // Champs Admin
        'username',
        'nom_complet',
        'role',
        // Champs Établissement
        'code',
        'nom',
        'arrondissement',
        'commune',
        'zone',
        'statut',
        // Informations Directeur
        'directeur_nom',
        'directeur_telephone',
        // Suivi Connexions
        'last_login_at',
        'login_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Vérifie si l'utilisateur est un admin
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un établissement
     */
    public function isEtablissement(): bool
    {
        return $this->type === 'etablissement';
    }

    /**
     * Vérifie si l'utilisateur est un super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->type === 'admin' && $this->role === 'super_admin';
    }

    /**
     * Scope pour filtrer les admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('type', 'admin');
    }

    /**
     * Scope pour filtrer les établissements
     */
    public function scopeEtablissements($query)
    {
        return $query->where('type', 'etablissement');
    }

    /**
     * Scope pour filtrer les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relation avec Etablissement
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
}
