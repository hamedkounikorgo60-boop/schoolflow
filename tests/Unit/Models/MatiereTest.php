<?php

namespace Tests\Unit\Models;

use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatiereTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $matiere = new Matiere();
        $expected = ['nom', 'coefficient', 'niveau', 'filiere'];

        $this->assertEquals($expected, $matiere->getFillable());
    }

    public function test_matiere_has_many_notes(): void
    {
        $matiere = Matiere::factory()->create();
        Note::factory()->count(2)->create(['matiere_id' => $matiere->id]);

        $this->assertCount(2, $matiere->notes);
    }

    public function test_matiere_belongs_to_many_enseignants(): void
    {
        $matiere = Matiere::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $matiere->enseignants()
        );
    }

    public function test_scope_for_classe_returns_query_builder(): void
    {
        Matiere::factory()->create();

        $result = Matiere::forClasse(1)->get();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    public function test_scope_for_classe_with_null_returns_all(): void
    {
        $initialCount = Matiere::count();
        Matiere::factory()->count(3)->create();

        $result = Matiere::forClasse(null)->get();

        $this->assertCount($initialCount + 3, $result);
    }
}
