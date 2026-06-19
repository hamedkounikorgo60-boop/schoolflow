<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EleveControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $gestionnaire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gestionnaire = User::factory()->create(['role' => 'gestionnaire']);
    }

    public function test_index_displays_list_of_eleves(): void
    {
        $classe = Classe::factory()->create();
        Eleve::factory()->count(3)->create(['classe_id' => $classe->id]);

        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.eleves.index'));

        $response->assertStatus(200);
    }

    public function test_create_form_is_displayed(): void
    {
        Classe::factory()->create();

        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.eleves.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_new_eleve(): void
    {
        $classe = Classe::factory()->create();

        $data = [
            'matricule' => 'MAT-0001',
            'nom' => 'Kounikorgo',
            'prenoms' => 'Hamed',
            'date_naissance' => '2015-05-10',
            'lieu_naissance' => 'Ouagadougou',
            'genre' => 'M',
            'classe_id' => $classe->id,
            'statut' => 'actif',
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.eleves.store'), $data);

        $response->assertRedirect(route('gestionnaire.eleves.index'));
        $this->assertDatabaseHas('eleves', ['matricule' => 'MAT-0001', 'nom' => 'Kounikorgo']);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.eleves.store'), []);

        $response->assertSessionHasErrors(['matricule', 'nom', 'prenoms', 'date_naissance', 'lieu_naissance', 'genre', 'classe_id', 'statut']);
    }

    public function test_store_validates_unique_matricule(): void
    {
        $classe = Classe::factory()->create();
        Eleve::factory()->create(['matricule' => 'MAT-DUPE', 'classe_id' => $classe->id]);

        $data = [
            'matricule' => 'MAT-DUPE',
            'nom' => 'Test',
            'prenoms' => 'User',
            'date_naissance' => '2015-01-01',
            'lieu_naissance' => 'City',
            'genre' => 'M',
            'classe_id' => $classe->id,
            'statut' => 'actif',
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->post(route('gestionnaire.eleves.store'), $data);

        $response->assertSessionHasErrors(['matricule']);
    }

    public function test_show_displays_eleve_details(): void
    {
        $eleve = Eleve::factory()->create();

        $response = $this->actingAs($this->gestionnaire)
            ->get(route('gestionnaire.eleves.show', $eleve));

        $response->assertStatus(200);
    }

    public function test_update_modifies_eleve(): void
    {
        $classe = Classe::factory()->create();
        $eleve = Eleve::factory()->create(['classe_id' => $classe->id]);

        $data = [
            'matricule' => $eleve->matricule,
            'nom' => 'NouveauNom',
            'prenoms' => $eleve->prenoms,
            'date_naissance' => $eleve->date_naissance,
            'lieu_naissance' => $eleve->lieu_naissance,
            'genre' => $eleve->genre,
            'classe_id' => $classe->id,
            'statut' => 'actif',
        ];

        $response = $this->actingAs($this->gestionnaire)
            ->put(route('gestionnaire.eleves.update', $eleve), $data);

        $response->assertRedirect(route('gestionnaire.eleves.index'));
        $this->assertDatabaseHas('eleves', ['id' => $eleve->id, 'nom' => 'NouveauNom']);
    }

    public function test_destroy_deletes_eleve(): void
    {
        $eleve = Eleve::factory()->create();

        $response = $this->actingAs($this->gestionnaire)
            ->delete(route('gestionnaire.eleves.destroy', $eleve));

        $response->assertRedirect(route('gestionnaire.eleves.index'));
        $this->assertDatabaseMissing('eleves', ['id' => $eleve->id]);
    }

    public function test_unauthenticated_user_cannot_access_eleves(): void
    {
        $response = $this->get(route('gestionnaire.eleves.index'));

        $response->assertRedirect();
    }

    public function test_enseignant_cannot_access_eleve_management(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($enseignant)
            ->get(route('gestionnaire.eleves.index'));

        $response->assertStatus(403);
    }
}
