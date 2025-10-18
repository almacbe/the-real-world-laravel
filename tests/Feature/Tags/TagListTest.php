<?php

namespace Tests\Feature\Tags;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagListTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_tags(): void
    {
        Tag::factory()->create(['name' => 'dragons']);
        Tag::factory()->create(['name' => 'training']);

        $response = $this->getJson('/api/tags');

        $response
            ->assertOk()
            ->assertJson([
                'tags' => ['dragons', 'training'],
            ]);
    }
}
