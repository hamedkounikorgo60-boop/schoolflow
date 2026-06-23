<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Database\Eloquent\Factories\Factory;

class EleveFactory extends Factory
{
    protected $model = Eleve::class;

    public function definition(): array
    {
        return [
            'matricule' => $this->faker->unique()->numerify('MAT-####'),
            'nom' => $this->faker->lastName(),
            'prenoms' => $this->faker->firstName(),
            'date_naissance' => $this->faker->date('Y-m-d', '-6 years'),
            'lieu_naissance' => $this->faker->city(),
            'genre' => $this->faker->randomElement(['M', 'F']),
            'telephone' => $this->faker->phoneNumber(),
            'adresse' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'classe_id' => Classe::factory(),
            'redoublant' => $this->faker->boolean(20),
            'statut' => 'actif',
        ];
    }
}
