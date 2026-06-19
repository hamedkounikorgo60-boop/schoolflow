<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eleve
 * Représente un élève de l'école
 */
class Eleve extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'matricule',        // Numéro d'identification unique
        'nom',             // Nom de famille
        'prenoms',         // Prénoms
        'date_naissance',  // Date de naissance
        'lieu_naissance',  // Lieu de naissance
        'genre',           // Genre (M/F)
        'telephone',       // Numéro de téléphone
        'adresse',         // Adresse
        'email',           // Email de l'élève
        'classe_id',       // ID de la classe
        'redoublant',      // Si c'est un redoublant
        'statut',          // Statut (actif/inactif)
        'photo',           // Photo de profil
    ];

    /**
     * Relation: Un élève appartient à une classe
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Relation: Un élève a plusieurs notes
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Relation: Un élève a plusieurs paiements
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
