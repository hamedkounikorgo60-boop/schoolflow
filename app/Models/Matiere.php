<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = ['nom', 'coefficient', 'niveau', 'filiere'];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
