<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_and_authenticated_home_render(): void
    {
        $this->get('/login')->assertOk();
        $this->actingAs(User::factory()->create())->get('/home')->assertOk();
    }

    public function test_password_login_still_works(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_api_rejects_a_boolean_token_before_querying_users(): void
    {
        // The standard migration has no api_token column: any lookup would fail.
        $this->json('GET', '/api/user', ['api_token' => true])->assertUnauthorized();
    }

    public function test_api_accepts_a_valid_bearer_token(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token')->nullable()->unique();
        });
        $user = User::factory()->create();
        $user->forceFill(['api_token' => 'valid-token'])->save();

        $this->withToken('valid-token')->getJson('/api/user')->assertOk()
            ->assertJsonPath('id', $user->id);
    }
}
