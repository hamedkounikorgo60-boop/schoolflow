<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClasseControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $gestionnaire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gestionnaire = User::factory()->create(['role' => 'gestionnaire']);
    }

    public function test_index_displays_list_of_classes(): void
    {
        Classe::factory()->count(3)->create();

        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.classes.index'));

        $response->assertStatus(200);
    }

    public function test_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.classes.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_new_classe(): void
    {
        $data = [
            'nom' => 'CP1-B',
            'niveau' => 'CP1',
            'frais_scolarite' => 75000,
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.classes.store'), $data);

        $response->assertRedirect(route('gestionnaire.classes.index'));
        $this->assertDatabaseHas('classes', ['nom' => 'CP1-B', 'niveau' => 'CP1']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.classes.store'), []);

        $response->assertSessionHasErrors(['nom', 'niveau', 'frais_scolarite']);
    }

    public function test_store_validates_unique_nom(): void
    {
        Classe::factory()->create(['nom' => 'CP1-A']);

        $data = [
            'nom' => 'CP1-A',
            'niveau' => 'CP1',
            'frais_scolarite' => 75000,
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.classes.store'), $data);

        $response->assertSessionHasErrors(['nom']);
    }

    public function test_store_validates_niveau_values(): void
    {
        $data = [
            'nom' => 'Invalid',
            'niveau' => 'INVALID',
            'frais_scolarite' => 75000,
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.classes.store'), $data);

        $response->assertSessionHasErrors(['niveau']);
    }

    public function test_update_modifies_classe(): void
    {
        $classe = Classe::factory()->create(['nom' => 'OldName', 'niveau' => 'CP1']);

        $data = [
            'nom' => 'NewName',
            'niveau' => 'CE1',
            'frais_scolarite' => 80000,
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->from(route('gestionnaire.classes.edit', $classe))
            ->put(route('gestionnaire.classes.update', $classe), $data);

        $response->assertRedirect(route('gestionnaire.classes.index'));
        $response->assertSessionHas('success');
    }

    public function test_destroy_deletes_classe_without_eleves(): void
    {
        $classe = Classe::factory()->create();

        $response = $this->actingAs($this->gestionnaire)
            ->delete(route('gestionnaire.classes.destroy', $classe));

        $response->assertRedirect(route('gestionnaire.classes.index'));
        $response->assertSessionHas('success');
    }

    public function test_destroy_prevents_deletion_of_classe_with_eleves(): void
    {
        $classe = Classe::factory()->create();
        Eleve::factory()->create(['classe_id' => $classe->id]);

        $response = $this->actingAs($this->gestionnaire)
            ->delete(route('gestionnaire.classes.destroy', $classe));

        $response->assertRedirect(route('gestionnaire.classes.index'));
        $this->assertDatabaseHas('classes', ['id' => $classe->id]);
    }

    public function test_enseignant_cannot_access_classe_management(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($enseignant)
            ->get(route('gestionnaire.classes.index'));

        $response->assertStatus(403);
    }
}
