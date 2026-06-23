<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'eleve_id' => Eleve::factory(),
            'matiere_id' => Matiere::factory(),
            'enseignant_id' => null,
            'note' => $this->faker->randomFloat(2, 0, 20),
            'trimestre' => $this->faker->randomElement(['1', '2', '3']),
        ];
    }
}
