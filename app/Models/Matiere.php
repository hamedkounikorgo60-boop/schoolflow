<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Matiere
 * Représente une matière/sujet enseigner dans l'école
 */
class Matiere extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'nom',           // Nom de la matière (ex: Mathématiques, Français)
        'coefficient',   // Coefficient pour le calcul de la moyenne
        'niveau',        // Niveau auquel s'applique la matière
        'filiere',       // Filière
    ];

    /**
     * Relation: Une matière a plusieurs notes
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Relation: Une matière est enseignée par plusieurs enseignants
     */
    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_matiere');
    }

    /**
     * Portée (Scope): Retourne les matières pour une classe donnée
     * Ne filtre pas par niveau - un enseignant peut enseigner une matière à toute classe
     */
    public function scopeForClasse($query, ?int $classeId)
    {
        return $query;
    }
}
