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
        return $this->hasMany(Matiere::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
