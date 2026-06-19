<?php

namespace Tests\Unit\Models;

use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $note = new Note();
        $expected = ['eleve_id', 'matiere_id', 'enseignant_id', 'note', 'trimestre'];

        $this->assertEquals($expected, $note->getFillable());
    }

    public function test_note_belongs_to_eleve(): void
    {
        $eleve = Eleve::factory()->create();
        $note = Note::factory()->create(['eleve_id' => $eleve->id]);

        $this->assertInstanceOf(Eleve::class, $note->eleve);
        $this->assertEquals($eleve->id, $note->eleve->id);
    }

    public function test_note_belongs_to_matiere(): void
    {
        $matiere = Matiere::factory()->create();
        $note = Note::factory()->create(['matiere_id' => $matiere->id]);

        $this->assertInstanceOf(Matiere::class, $note->matiere);
        $this->assertEquals($matiere->id, $note->matiere->id);
    }

    public function test_note_can_have_null_enseignant_id(): void
    {
        $note = Note::factory()->create(['enseignant_id' => null]);

        $this->assertNull($note->enseignant_id);
    }

    public function test_can_create_note_with_valid_data(): void
    {
        $eleve = Eleve::factory()->create();
        $matiere = Matiere::factory()->create();

        $note = Note::create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'enseignant_id' => null,
            'note' => 15.5,
            'trimestre' => '1',
        ]);

        $this->assertDatabaseHas('notes', [
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 15.5,
            'trimestre' => '1',
        ]);
    }
}
