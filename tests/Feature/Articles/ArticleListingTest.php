<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ArticleListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_articles_with_filters(): void
    {
        $author = User::factory()->create(['username' => 'jake']);
        $otherAuthor = User::factory()->create(['username' => 'jane']);
        $favoriter = User::factory()->create(['username' => 'doe']);

        $firstArticle = Article::factory()->create([
            'author_id' => $author->id,
            'title' => 'First Article',
            'slug' => 'first-article',
            'created_at' => now()->subDay(),
        ]);
        $secondArticle = Article::factory()->create([
            'author_id' => $otherAuthor->id,
            'title' => 'Second Article',
            'slug' => 'second-article',
            'created_at' => now(),
        ]);
        $thirdArticle = Article::factory()->create([
            'author_id' => $author->id,
            'title' => 'Third Article',
            'slug' => 'third-article',
            'created_at' => now()->subDays(2),
        ]);

        $dragons = Tag::factory()->create(['name' => 'dragons']);
        $training = Tag::factory()->create(['name' => 'training']);
        $otherTag = Tag::factory()->create(['name' => 'other']);

        $firstArticle->tags()->attach([$dragons->id, $training->id]);
        $secondArticle->tags()->attach([$otherTag->id]);

        $firstArticle->favoritedBy()->attach($favoriter);

        $response = $this->getJson('/api/articles?tag=dragons&author=jake&favorited=doe&limit=5&offset=0');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('articlesCount', 1)
                ->has('articles', 1)
                ->has('articles.0', fn (AssertableJson $article) => $article
                    ->where('slug', 'first-article')
                    ->where('title', 'First Article')
                    ->where('favorited', false)
                    ->where('favoritesCount', 1)
                    ->where('tagList', ['dragons', 'training'])
                    ->etc()
                )
            );
    }

    public function test_authenticated_user_receives_feed(): void
    {
        $viewer = User::factory()->create(['username' => 'viewer']);
        $followed = User::factory()->create(['username' => 'followed']);
        $unfollowed = User::factory()->create(['username' => 'stranger']);

        $viewer->following()->attach($followed);

        Article::factory()->create([
            'author_id' => $followed->id,
            'slug' => 'followed-article-1',
            'created_at' => now(),
        ]);
        Article::factory()->create([
            'author_id' => $followed->id,
            'slug' => 'followed-article-2',
            'created_at' => now()->subMinutes(10),
        ]);
        Article::factory()->create([
            'author_id' => $unfollowed->id,
            'slug' => 'stranger-article',
        ]);

        $token = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->getJson('/api/articles/feed?limit=10&offset=0');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('articlesCount', 2)
                ->has('articles', 2)
                ->has('articles.0', fn (AssertableJson $article) => $article
                    ->where('slug', 'followed-article-1')
                    ->etc()
                )
            );
    }

    public function test_user_can_favorite_and_unfavorite_article(): void
    {
        $author = User::factory()->create();
        $viewer = User::factory()->create();

        $article = Article::factory()->create([
            'author_id' => $author->id,
            'slug' => 'favorite-article',
        ]);

        $token = JWTAuth::fromUser($viewer);

        $favoriteResponse = $this->withHeader('Authorization', 'Token '.$token)
            ->postJson('/api/articles/favorite-article/favorite');

        $favoriteResponse
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('article', fn (AssertableJson $articleJson) => $articleJson
                    ->where('slug', 'favorite-article')
                    ->where('favorited', true)
                    ->where('favoritesCount', 1)
                    ->etc()
                )
            );

        $this->assertDatabaseHas('favorites', [
            'user_id' => $viewer->id,
            'article_id' => $article->id,
        ]);

        $unfavoriteResponse = $this->withHeader('Authorization', 'Token '.$token)
            ->deleteJson('/api/articles/favorite-article/favorite');

        $unfavoriteResponse
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('article', fn (AssertableJson $articleJson) => $articleJson
                    ->where('slug', 'favorite-article')
                    ->where('favorited', false)
                    ->where('favoritesCount', 0)
                    ->etc()
                )
            );

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $viewer->id,
            'article_id' => $article->id,
        ]);
    }
}
