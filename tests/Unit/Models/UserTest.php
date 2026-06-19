<?php

namespace Tests\Unit\Models;

use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes(): void
    {
        $user = new User();

        $this->assertEquals(['name', 'email', 'password', 'role'], $user->getFillable());
    }

    public function test_hidden_attributes(): void
    {
        $user = new User();

        $this->assertEquals(['password', 'remember_token'], $user->getHidden());
    }

    public function test_is_gestionnaire_returns_true_for_gestionnaire(): void
    {
        $user = User::factory()->create(['role' => 'gestionnaire']);

        $this->assertTrue($user->isGestionnaire());
    }

    public function test_is_gestionnaire_returns_false_for_enseignant(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);

        $this->assertFalse($user->isGestionnaire());
    }

    public function test_is_enseignant_returns_true_for_enseignant(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);

        $this->assertTrue($user->isEnseignant());
    }

    public function test_is_enseignant_returns_false_for_gestionnaire(): void
    {
        $user = User::factory()->create(['role' => 'gestionnaire']);

        $this->assertFalse($user->isEnseignant());
    }

    public function test_user_has_one_enseignant(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);
        Enseignant::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Enseignant::class, $user->enseignant);
    }

    public function test_user_belongs_to_many_classes(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class, $user->classes());
    }

    public function test_password_is_hashed_via_cast(): void
    {
        $user = User::factory()->create(['password' => 'plain_password']);

        $this->assertNotEquals('plain_password', $user->password);
    }
}
