<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'montant',
        'type_paiement',
        'trimestre',
        'mois',
        'date_paiement',
        'statut',
        'recu_numero',
        'observation',
    ];

    protected $casts = [
        'date_paiement' => 'date',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
