<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'eleve_id' => Eleve::factory(),
            'montant' => $this->faker->randomFloat(2, 5000, 100000),
            'type_paiement' => $this->faker->randomElement(['scolarite', 'inscription', 'cantine', 'transport', 'fournitures', 'autre']),
            'trimestre' => $this->faker->randomElement(['1', '2', '3']),
            'mois' => $this->faker->month(),
            'date_paiement' => $this->faker->date(),
            'statut' => 'paye',
            'recu_numero' => 'REC-' . $this->faker->unique()->numerify('########-####'),
            'observation' => $this->faker->optional()->sentence(),
        ];
    }
}
