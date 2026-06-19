<?php

namespace Database\Factories;

use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnseignantFactory extends Factory
{
    protected $model = Enseignant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialite' => $this->faker->randomElement(['Mathématiques', 'Français', 'Sciences', 'Histoire']),
            'telephone' => $this->faker->phoneNumber(),
        ];
    }
}
