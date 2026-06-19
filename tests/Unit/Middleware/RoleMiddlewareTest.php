<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private RoleMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new RoleMiddleware();
    }

    public function test_allows_user_with_correct_role(): void
    {
        $user = User::factory()->create(['role' => 'gestionnaire']);
        Auth::login($user);

        $request = Request::create('/test', 'GET');
        $response = $this->middleware->handle($request, fn ($req) => response('OK'), 'gestionnaire');

        $this->assertEquals('OK', $response->getContent());
    }

    public function test_blocks_user_with_wrong_role(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);
        Auth::login($user);

        $request = Request::create('/test', 'GET');

        $this->expectException(HttpException::class);
        $this->middleware->handle($request, fn ($req) => response('OK'), 'gestionnaire');
    }

    public function test_blocks_unauthenticated_user(): void
    {
        $request = Request::create('/test', 'GET');

        $this->expectException(HttpException::class);
        $this->middleware->handle($request, fn ($req) => response('OK'), 'gestionnaire');
    }

    public function test_enseignant_role_allowed_for_enseignant(): void
    {
        $user = User::factory()->create(['role' => 'enseignant']);
        Auth::login($user);

        $request = Request::create('/test', 'GET');
        $response = $this->middleware->handle($request, fn ($req) => response('OK'), 'enseignant');

        $this->assertEquals('OK', $response->getContent());
    }
}
