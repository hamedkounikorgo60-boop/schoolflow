<?php

namespace Tests\Unit\Models;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClasseTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $classe = new Classe();

        $this->assertEquals(['nom', 'niveau', 'frais_scolarite'], $classe->getFillable());
    }

    public function test_classe_has_many_eleves(): void
    {
        $classe = Classe::factory()->create();
        Eleve::factory()->count(3)->create(['classe_id' => $classe->id]);

        $this->assertCount(3, $classe->eleves);
    }

    public function test_classe_has_many_matieres(): void
    {
        $classe = Classe::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $classe->matieres());
    }

    public function test_classe_belongs_to_many_enseignants(): void
    {
        $classe = Classe::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $classe->enseignants());
    }

    public function test_frais_par_type_returns_scolarite(): void
    {
        $classe = Classe::factory()->create(['frais_scolarite' => 150000]);

        $this->assertEquals(150000.0, $classe->fraisParType('scolarite'));
    }

    public function test_frais_par_type_returns_inscription(): void
    {
        $classe = Classe::factory()->create(['frais_inscription' => 25000]);

        $this->assertEquals(25000.0, $classe->fraisParType('inscription'));
    }

    public function test_frais_par_type_returns_cantine(): void
    {
        $classe = Classe::factory()->create(['frais_cantine' => 18000]);

        $this->assertEquals(18000.0, $classe->fraisParType('cantine'));
    }

    public function test_frais_par_type_returns_transport(): void
    {
        $classe = Classe::factory()->create(['frais_transport' => 12000]);

        $this->assertEquals(12000.0, $classe->fraisParType('transport'));
    }

    public function test_frais_par_type_returns_fournitures(): void
    {
        $classe = Classe::factory()->create(['frais_fournitures' => 8000]);

        $this->assertEquals(8000.0, $classe->fraisParType('fournitures'));
    }

    public function test_frais_par_type_returns_autre(): void
    {
        $classe = Classe::factory()->create(['autres_frais' => 5000]);

        $this->assertEquals(5000.0, $classe->fraisParType('autre'));
    }

    public function test_frais_par_type_returns_zero_for_unknown_type(): void
    {
        $classe = Classe::factory()->create();

        $this->assertEquals(0, $classe->fraisParType('unknown'));
    }

    public function test_frais_total_annuel_sums_all_fee_types(): void
    {
        $classe = Classe::factory()->create([
            'frais_scolarite' => 100000,
            'frais_inscription' => 20000,
            'frais_cantine' => 15000,
            'frais_transport' => 10000,
            'frais_fournitures' => 8000,
            'autres_frais' => 5000,
        ]);

        $this->assertEquals(158000.0, $classe->fraisTotalAnnuel());
    }

    public function test_frais_trimestriel_divides_by_three_for_scolarite(): void
    {
        $classe = Classe::factory()->create(['frais_scolarite' => 90000]);

        $this->assertEquals(30000.0, $classe->fraisTrimestriel('scolarite'));
    }

    public function test_frais_trimestriel_divides_by_three_for_cantine(): void
    {
        $classe = Classe::factory()->create(['frais_cantine' => 27000]);

        $this->assertEquals(9000.0, $classe->fraisTrimestriel('cantine'));
    }

    public function test_frais_trimestriel_divides_by_three_for_transport(): void
    {
        $classe = Classe::factory()->create(['frais_transport' => 18000]);

        $this->assertEquals(6000.0, $classe->fraisTrimestriel('transport'));
    }

    public function test_frais_trimestriel_does_not_divide_for_inscription(): void
    {
        $classe = Classe::factory()->create(['frais_inscription' => 25000]);

        $this->assertEquals(25000.0, $classe->fraisTrimestriel('inscription'));
    }

    public function test_frais_trimestriel_does_not_divide_for_fournitures(): void
    {
        $classe = Classe::factory()->create(['frais_fournitures' => 10000]);

        $this->assertEquals(10000.0, $classe->fraisTrimestriel('fournitures'));
    }
}
