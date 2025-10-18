<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/users', [
            'user' => [
                'username' => 'jake',
                'email' => 'jake@example.com',
                'password' => 'secret123',
            ],
        ]);

        $response
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('user', fn (AssertableJson $user) => $user
                    ->where('email', 'jake@example.com')
                    ->where('username', 'jake')
                    ->where('bio', null)
                    ->where('image', null)
                    ->has('token')
                )
            );

        $this->assertDatabaseHas('users', [
            'email' => 'jake@example.com',
            'username' => 'jake',
        ]);
    }
}

