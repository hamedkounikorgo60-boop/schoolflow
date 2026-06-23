<?php

namespace Tests\Unit\Models;

use App\Models\Enseignant;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnseignantTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $enseignant = new Enseignant();

        $this->assertEquals(['user_id', 'specialite', 'telephone'], $enseignant->getFillable());
    }

    public function test_enseignant_belongs_to_user(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);
        $enseignant = Enseignant::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $enseignant->user);
        $this->assertEquals($user->id, $enseignant->user->id);
    }

    public function test_enseignant_belongs_to_many_matieres(): void
    {
        $enseignant = Enseignant::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $enseignant->matieres()
        );
    }

    public function test_enseignant_has_many_notes(): void
    {
        $enseignant = Enseignant::factory()->create();
        Note::factory()->count(2)->create(['enseignant_id' => $enseignant->id]);

        $this->assertCount(2, $enseignant->notes);
    }

    public function test_enseignant_classes_relationship(): void
    {
        $enseignant = Enseignant::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $enseignant->classes()
        );
    }
}
