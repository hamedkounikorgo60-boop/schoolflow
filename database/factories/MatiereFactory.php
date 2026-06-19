<?php

namespace Database\Factories;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatiereFactory extends Factory
{
    protected $model = Matiere::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->unique()->randomElement([
                'Mathématiques', 'Français', 'Sciences', 'Histoire',
                'Géographie', 'Anglais', 'EPS', 'Arts',
            ]),
            'coefficient' => $this->faker->numberBetween(1, 5),
            'niveau' => $this->faker->randomElement(['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2']),
            'filiere' => 'Générale',
        ];
    }
}
