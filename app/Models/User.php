<?php
namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modèle User
 * Représente un utilisateur de l'application (Gestionnaire, Enseignant, Parent)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'name',             // Nom complet de l'utilisateur
        'email',            // Email (identifiant de connexion)
        'password',         // Mot de passe (haché)
        'role',             // Rôle (gestionnaire, enseignant, parent)
        'telephone',        // Téléphone
        'adresse',          // Adresse
    ];

    /**
     * Les attributs qui ne doivent pas être visibles dans les sérialisations
     */
    protected $hidden = [
        'password',         // Ne pas retourner le mot de passe
        'remember_token',   // Token de mémorisation
    ];

    /**
     * Convertit les attributs au bon type de données
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // Converti en DateTime
            'password' => 'hashed',             // Le mot de passe est haché
        ];
    }

    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }

    public function isEnseignant(): bool
    {
        return $this->role === 'enseignant';
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_enseignant');
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'parent_id');
    }
}
