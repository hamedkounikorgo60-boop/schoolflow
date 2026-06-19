<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'role' => 'gestionnaire',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'gestionnaire']);

        $response = $this->actingAs($user)
            ->post(route('logout'));

        $response->assertRedirect();
        $this->assertGuest();
    }

    public function test_root_url_redirects_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
