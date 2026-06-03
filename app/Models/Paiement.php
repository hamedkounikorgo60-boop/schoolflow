<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'eleve_id',
        'montant',
        'type_paiement',
        'trimestre',
        'mois',
        'date_paiement',
        'statut',
        'recu_numero',
    ];

    protected $casts = [
        'date_paiement' => 'date',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
