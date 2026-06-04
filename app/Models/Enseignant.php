<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $fillable = ['user_id', 'specialite', 'telephone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_matiere');
    }

    public function classes()
    {
        return $this->belongsToMany(
            Classe::class,
            'classe_enseignant',
            'user_id',
            'classe_id',
            'user_id'
        );
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
