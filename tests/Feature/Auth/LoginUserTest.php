<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'jake@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->postJson('/api/users/login', [
            'user' => [
                'email' => 'jake@example.com',
                'password' => 'secret123',
            ],
        ]);

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('user', fn (AssertableJson $userJson) => $userJson
                    ->where('email', $user->email)
                    ->where('username', $user->username)
                    ->where('bio', $user->bio)
                    ->where('image', $user->image)
                    ->has('token')
                )
            );
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'jake@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->postJson('/api/users/login', [
            'user' => [
                'email' => 'jake@example.com',
                'password' => 'wrong-password',
            ],
        ]);

        $response->assertUnprocessable();
    }
}
