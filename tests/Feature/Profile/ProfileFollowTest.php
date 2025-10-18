<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ProfileFollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_profile(): void
    {
        $user = User::factory()->create([
            'username' => 'jake',
            'bio' => 'Bio',
            'image' => 'https://example.com/avatar.png',
        ]);

        $response = $this->getJson('/api/profiles/jake');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('profile', fn (AssertableJson $profile) => $profile
                    ->where('username', $user->username)
                    ->where('bio', $user->bio)
                    ->where('image', $user->image)
                    ->where('following', false)
                )
            );
    }

    public function test_authenticated_user_sees_following_state(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create([
            'username' => 'jake',
        ]);

        $viewer->following()->attach($author);

        $token = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->getJson('/api/profiles/jake');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('profile', fn (AssertableJson $profile) => $profile
                    ->where('username', 'jake')
                    ->where('following', true)
                    ->etc()
                )
            );
    }

    public function test_user_can_follow_profile(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create([
            'username' => 'jake',
        ]);

        $token = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->postJson('/api/profiles/jake/follow');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('profile', fn (AssertableJson $profile) => $profile
                    ->where('username', 'jake')
                    ->where('following', true)
                    ->etc()
                )
            );

        $this->assertTrue($viewer->fresh()->following()->whereKey($author->id)->exists());
    }

    public function test_user_can_unfollow_profile(): void
    {
        $viewer = User::factory()->create();
        $author = User::factory()->create([
            'username' => 'jake',
        ]);

        $viewer->following()->attach($author);
        $token = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->deleteJson('/api/profiles/jake/follow');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('profile', fn (AssertableJson $profile) => $profile
                    ->where('username', 'jake')
                    ->where('following', false)
                    ->etc()
                )
            );

        $this->assertFalse($viewer->fresh()->following()->whereKey($author->id)->exists());
    }

    public function test_user_cannot_follow_themself(): void
    {
        $viewer = User::factory()->create([
            'username' => 'jake',
        ]);

        $token = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->postJson('/api/profiles/jake/follow');

        $response->assertUnprocessable();
    }
}
