<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Modèle Classe
 * Représente une classe de l'école
 */
class Classe extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'nom',               // Nom de la classe (ex: CP1, CE1, CM2)
        'niveau',            // Niveau (ex: CP1, CE1, CM1, CM2)
        'frais_scolarite',   // Montant des frais de scolarité
    ];

    /**
     * Relation: Une classe a plusieurs élèves
     */
    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    /**
     * Relation: Une classe a plusieurs matières
     */
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    /**
     * Relation: Une classe a plusieurs enseignants
     */
    public function enseignants()
    {
        return $this->belongsToMany(User::class, 'classe_enseignant');
    }

    public function fraisParType(string $type): float
    {
        return match ($type) {
            'scolarite'   => (float) ($this->frais_scolarite ?? 0),
            'inscription' => (float) ($this->frais_inscription ?? 0),
            'cantine'     => (float) ($this->frais_cantine ?? 0),
            'transport'   => (float) ($this->frais_transport ?? 0),
            'fournitures' => (float) ($this->frais_fournitures ?? 0),
            'autre'       => (float) ($this->autres_frais ?? 0),
            default       => 0,
        };
    }

    /** Somme de tous les frais annuels configurés pour la classe */
    public function fraisTotalAnnuel(): float
    {
        return $this->fraisParType('scolarite')
            + $this->fraisParType('inscription')
            + $this->fraisParType('cantine')
            + $this->fraisParType('transport')
            + $this->fraisParType('fournitures')
            + $this->fraisParType('autre');
    }

    /** Montant dû pour un trimestre (scolarité/cantine/transport = annuel ÷ 3) */
    public function fraisTrimestriel(string $type): float
    {
        $annuel = $this->fraisParType($type);

        return in_array($type, ['scolarite', 'cantine', 'transport'], true)
            ? $annuel / 3
            : $annuel;
    }
}
