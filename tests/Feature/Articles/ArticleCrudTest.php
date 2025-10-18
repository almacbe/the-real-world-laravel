<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ArticleCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_article(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Token '.$token)->postJson('/api/articles', [
            'article' => [
                'title' => 'How to train your dragon',
                'description' => 'Ever wonder how?',
                'body' => 'You have to believe',
                'tagList' => ['training', 'dragons'],
            ],
        ]);

        $response
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('article', fn (AssertableJson $article) => $article
                    ->where('title', 'How to train your dragon')
                    ->where('description', 'Ever wonder how?')
                    ->where('body', 'You have to believe')
                    ->where('favorited', false)
                    ->where('favoritesCount', 0)
                    ->has('slug')
                    ->where('tagList', ['dragons', 'training'])
                    ->has('author', fn (AssertableJson $author) => $author
                        ->where('username', $user->username)
                        ->where('following', false)
                        ->etc()
                    )
                    ->etc()
                )
            );

        $article = Article::query()->where('title', 'How to train your dragon')->first();

        $this->assertNotNull($article);
        $this->assertDatabaseHas('article_tag', [
            'article_id' => $article->id,
        ]);
    }

    public function test_guest_can_view_article(): void
    {
        $article = Article::factory()->create([
            'title' => 'Article title',
            'slug' => 'article-title',
        ]);

        $tags = Tag::factory()->count(2)->create();
        $article->tags()->attach($tags->pluck('id'));

        $response = $this->getJson('/api/articles/'.$article->slug);

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('article', fn (AssertableJson $articleJson) => $articleJson
                    ->where('slug', 'article-title')
                    ->where('favorited', false)
                    ->has('tagList')
                    ->has('author', fn (AssertableJson $author) => $author
                        ->where('username', $article->author->username)
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_author_can_update_article(): void
    {
        $article = Article::factory()->create([
            'title' => 'Original title',
            'slug' => 'original-title',
        ]);

        $token = JWTAuth::fromUser($article->author);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->putJson('/api/articles/'.$article->slug, [
                'article' => [
                    'title' => 'Updated Title',
                    'description' => 'Updated description',
                ],
            ]);

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('article', fn (AssertableJson $articleJson) => $articleJson
                    ->where('title', 'Updated Title')
                    ->where('description', 'Updated description')
                    ->where('slug', 'updated-title')
                    ->etc()
                )
            );

        $article->refresh();

        $this->assertEquals('Updated Title', $article->title);
        $this->assertEquals('Updated description', $article->description);
        $this->assertEquals('updated-title', $article->slug);
    }

    public function test_non_author_cannot_update_article(): void
    {
        $article = Article::factory()->create();
        $otherUser = User::factory()->create();
        $token = JWTAuth::fromUser($otherUser);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->putJson('/api/articles/'.$article->slug, [
                'article' => [
                    'title' => 'Hacked',
                ],
            ]);

        $response->assertForbidden();
    }

    public function test_author_can_delete_article(): void
    {
        $article = Article::factory()->create();
        $token = JWTAuth::fromUser($article->author);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->deleteJson('/api/articles/'.$article->slug);

        $response->assertNoContent();

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }
}
