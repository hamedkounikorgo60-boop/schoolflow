<?php

namespace Database\Factories;

use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->randomElement(['CP1-A', 'CP2-A', 'CE1-A', 'CE2-A', 'CM1-A', 'CM2-A']),
            'niveau' => $this->faker->randomElement(['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2']),
            'frais_scolarite' => $this->faker->randomFloat(2, 50000, 200000),
            'frais_inscription' => $this->faker->randomFloat(2, 10000, 50000),
            'frais_cantine' => $this->faker->randomFloat(2, 5000, 30000),
            'frais_transport' => $this->faker->randomFloat(2, 5000, 25000),
            'frais_fournitures' => $this->faker->randomFloat(2, 5000, 20000),
            'autres_frais' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
