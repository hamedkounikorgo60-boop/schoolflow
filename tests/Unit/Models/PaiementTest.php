<?php

namespace Tests\Unit\Models;

use App\Models\Eleve;
use App\Models\Paiement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $paiement = new Paiement();
        $expected = [
            'eleve_id', 'montant', 'type_paiement', 'trimestre',
            'mois', 'date_paiement', 'statut', 'recu_numero', 'observation',
        ];

        $this->assertEquals($expected, $paiement->getFillable());
    }

    public function test_date_paiement_is_cast_to_date(): void
    {
        $paiement = Paiement::factory()->create(['date_paiement' => '2026-03-15']);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $paiement->date_paiement);
    }

    public function test_paiement_belongs_to_eleve(): void
    {
        $eleve = Eleve::factory()->create();
        $paiement = Paiement::factory()->create(['eleve_id' => $eleve->id]);

        $this->assertInstanceOf(Eleve::class, $paiement->eleve);
        $this->assertEquals($eleve->id, $paiement->eleve->id);
    }

    public function test_can_create_paiement_with_factory(): void
    {
        $paiement = Paiement::factory()->create();

        $this->assertDatabaseHas('paiements', ['id' => $paiement->id]);
    }

    public function test_paiement_statut_can_be_paye(): void
    {
        $paiement = Paiement::factory()->create(['statut' => 'paye']);

        $this->assertEquals('paye', $paiement->statut);
    }

    public function test_paiement_statut_can_be_impaye(): void
    {
        $paiement = Paiement::factory()->create(['statut' => 'impaye']);

        $this->assertEquals('impaye', $paiement->statut);
    }

    public function test_paiement_observation_can_be_null(): void
    {
        $paiement = Paiement::factory()->create(['observation' => null]);

        $this->assertNull($paiement->observation);
    }
}
