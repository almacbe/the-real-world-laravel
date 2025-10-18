<?php

namespace Tests\Feature\Error;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ErrorResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_errors_are_formatted_to_spec(): void
    {
        $response = $this->postJson('/api/users', [
            'user' => [
                'username' => '',
                'email' => 'invalid-email',
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('errors', fn ($errors) => collect($errors)
                    ->pluck('field')
                    ->contains('user.username')
                    && collect($errors)
                        ->pluck('field')
                        ->contains('user.email')
                )
            );
    }

    public function test_not_found_errors_are_formatted_to_spec(): void
    {
        $response = $this->getJson('/api/articles/non-existent');

        $response
            ->assertNotFound()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('errors', 1)
                ->where('errors.0.message', 'Resource not found.')
                ->where('errors.0.code', 404)
            );
    }
}
