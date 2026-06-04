<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Note
 * Représente une note obtenue par un élève dans une matière pour un trimestre
 */
class Note extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'eleve_id',        // ID de l'élève
        'matiere_id',      // ID de la matière
        'enseignant_id',   // ID de l'enseignant (optionnel)
        'note',            // Valeur de la note (0-20)
        'trimestre',       // Trimestre (trimestre1, trimestre2, trimestre3)
    ];

    /**
     * Relation: Une note appartient à un élève
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    /**
     * Relation: Une note appartient à une matière
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
