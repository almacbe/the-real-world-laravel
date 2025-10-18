<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class CurrentUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_current_user(): void
    {
        $user = User::factory()->create([
            'username' => 'jake',
            'email' => 'jake@example.com',
            'bio' => 'I work at statefarm',
            'image' => 'https://example.com/avatar.png',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->getJson('/api/user');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('user', fn (AssertableJson $userJson) => $userJson
                    ->where('email', 'jake@example.com')
                    ->where('username', 'jake')
                    ->where('bio', 'I work at statefarm')
                    ->where('image', 'https://example.com/avatar.png')
                    ->has('token')
                )
            );
    }

    public function test_it_updates_the_current_user(): void
    {
        $user = User::factory()->create([
            'username' => 'jake',
            'email' => 'jake@example.com',
            'bio' => null,
            'image' => null,
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->putJson('/api/user', [
                'user' => [
                    'username' => 'updated_jake',
                    'email' => 'updated@example.com',
                    'password' => 'newsecret123',
                    'bio' => 'Updated bio',
                    'image' => 'https://example.com/new.png',
                ],
            ]);

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('user', fn (AssertableJson $userJson) => $userJson
                    ->where('email', 'updated@example.com')
                    ->where('username', 'updated_jake')
                    ->where('bio', 'Updated bio')
                    ->where('image', 'https://example.com/new.png')
                    ->has('token')
                )
            );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated@example.com',
            'username' => 'updated_jake',
            'bio' => 'Updated bio',
            'image' => 'https://example.com/new.png',
        ]);

        $this->assertTrue(Hash::check('newsecret123', $user->fresh()->password));
    }
}
