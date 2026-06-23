<?php

namespace Tests\Unit\Models;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Note;
use App\Models\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EleveTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $eleve = new Eleve();
        $expected = [
            'matricule', 'nom', 'prenoms', 'date_naissance', 'lieu_naissance',
            'genre', 'telephone', 'adresse', 'email', 'classe_id', 'redoublant',
            'statut', 'photo',
        ];

        $this->assertEquals($expected, $eleve->getFillable());
    }

    public function test_eleve_belongs_to_classe(): void
    {
        $classe = Classe::factory()->create();
        $eleve = Eleve::factory()->create(['classe_id' => $classe->id]);

        $this->assertInstanceOf(Classe::class, $eleve->classe);
        $this->assertEquals($classe->id, $eleve->classe->id);
    }

    public function test_eleve_has_many_notes(): void
    {
        $eleve = Eleve::factory()->create();
        Note::factory()->count(3)->create(['eleve_id' => $eleve->id]);

        $this->assertCount(3, $eleve->notes);
    }

    public function test_eleve_has_many_paiements(): void
    {
        $eleve = Eleve::factory()->create();
        Paiement::factory()->count(2)->create(['eleve_id' => $eleve->id]);

        $this->assertCount(2, $eleve->paiements);
    }

    public function test_can_create_eleve_with_factory(): void
    {
        $eleve = Eleve::factory()->create();

        $this->assertDatabaseHas('eleves', ['id' => $eleve->id]);
        $this->assertNotEmpty($eleve->matricule);
        $this->assertNotEmpty($eleve->nom);
        $this->assertNotEmpty($eleve->prenoms);
    }

    public function test_eleve_statut_defaults_to_actif(): void
    {
        $eleve = Eleve::factory()->create();

        $this->assertEquals('actif', $eleve->statut);
    }
}
