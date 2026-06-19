<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $gestionnaire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gestionnaire = User::factory()->create(['role' => 'gestionnaire']);
    }

    public function test_index_displays_notes_page(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.notes.index'));

        $response->assertStatus(200);
    }

    public function test_index_with_classe_id_shows_eleves(): void
    {
        $classe = Classe::factory()->create();
        $eleve = Eleve::factory()->create(['classe_id' => $classe->id, 'statut' => 'actif']);
        $matiere = Matiere::factory()->create();
        Note::factory()->create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'trimestre' => 'trimestre1',
            'note' => 15,
        ]);

        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.notes.index', ['classe_id' => $classe->id, 'trimestre' => 1]));

        $response->assertStatus(200);
    }

    public function test_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.notes.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_new_note(): void
    {
        $eleve = Eleve::factory()->create();
        $matiere = Matiere::factory()->create();

        $data = [
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 15.5,
            'trimestre' => 'trimestre1',
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.notes.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('notes', [
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 15.5,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.notes.store'), []);

        $response->assertSessionHasErrors(['eleve_id', 'matiere_id', 'note', 'trimestre']);
    }

    public function test_store_validates_note_range(): void
    {
        $eleve = Eleve::factory()->create();
        $matiere = Matiere::factory()->create();

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.notes.store'), [
                'eleve_id' => $eleve->id,
                'matiere_id' => $matiere->id,
                'note' => 25,
                'trimestre' => 'trimestre1',
            ]);

        $response->assertSessionHasErrors(['note']);
    }

    public function test_store_updates_existing_note_for_same_eleve_matiere_trimestre(): void
    {
        $eleve = Eleve::factory()->create();
        $matiere = Matiere::factory()->create();

        Note::factory()->create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'trimestre' => 'trimestre1',
            'note' => 10,
        ]);

        $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.notes.store'), [
                'eleve_id' => $eleve->id,
                'matiere_id' => $matiere->id,
                'note' => 18,
                'trimestre' => 'trimestre1',
            ]);

        $this->assertDatabaseCount('notes', 1);
        $this->assertDatabaseHas('notes', [
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 18,
        ]);
    }

    public function test_classement_page_is_accessible(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.notes.classement'));

        $response->assertStatus(200);
    }

    public function test_enseignant_cannot_access_gestionnaire_notes(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($enseignant)
            ->get(route('gestionnaire.notes.index'));

        $response->assertStatus(403);
    }
}
