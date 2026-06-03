<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = [
        'matricule',
        'nom',
        'prenoms',
        'date_naissance',
        'lieu_naissance',
        'genre',
        'telephone',
        'adresse',
        'email',
        'classe_id',
        'redoublant',
        'statut',
        'photo',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
