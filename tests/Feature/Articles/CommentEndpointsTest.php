<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class CommentEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_comment(): void
    {
        $author = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $author->id,
            'slug' => 'comment-article',
        ]);

        $viewer = User::factory()->create(['username' => 'commenter']);
        $viewerToken = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$viewerToken)
            ->postJson('/api/articles/comment-article/comments', [
                'comment' => [
                    'body' => 'Thank you for this article',
                ],
            ]);

        $response
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('comment', fn (AssertableJson $comment) => $comment
                    ->where('body', 'Thank you for this article')
                    ->where('author.username', 'commenter')
                    ->where('author.following', false)
                    ->etc()
                )
            );

        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'author_id' => $viewer->id,
            'body' => 'Thank you for this article',
        ]);
    }

    public function test_it_lists_comments_with_following_state(): void
    {
        $author = User::factory()->create(['username' => 'jake']);
        $article = Article::factory()->create([
            'author_id' => $author->id,
            'slug' => 'listing-article',
        ]);

        $viewer = User::factory()->create(['username' => 'viewer']);
        $viewer->following()->attach($author);

        $commentByAuthor = Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $author->id,
            'body' => 'Author comment',
        ]);

        $otherUser = User::factory()->create(['username' => 'other']);
        Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $otherUser->id,
            'body' => 'Other comment',
        ]);

        $token = JWTAuth::fromUser($viewer);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->getJson('/api/articles/listing-article/comments');

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('comments.0.body', 'Author comment')
                ->where('comments.0.author.following', true)
                ->where('comments.1.author.following', false)
            );

        $guestResponse = $this->withHeaders(['Authorization' => ''])
            ->getJson('/api/articles/listing-article/comments');

        $guestResponse
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('comments.0.author.following', false)
                ->where('comments.1.author.following', false)
            );
    }

    public function test_comment_author_can_delete_comment(): void
    {
        $articleAuthor = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $articleAuthor->id,
            'slug' => 'delete-article',
        ]);

        $commentAuthor = User::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $commentAuthor->id,
            'body' => 'Delete me',
        ]);

        $token = JWTAuth::fromUser($commentAuthor);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->deleteJson('/api/articles/delete-article/comments/'.$comment->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_non_author_cannot_delete_comment(): void
    {
        $articleAuthor = User::factory()->create();
        $article = Article::factory()->create([
            'author_id' => $articleAuthor->id,
            'slug' => 'protected-article',
        ]);

        $commentAuthor = User::factory()->create();
        $comment = Comment::factory()->create([
            'article_id' => $article->id,
            'author_id' => $commentAuthor->id,
        ]);

        $otherUser = User::factory()->create();
        $token = JWTAuth::fromUser($otherUser);

        $response = $this->withHeader('Authorization', 'Token '.$token)
            ->deleteJson('/api/articles/protected-article/comments/'.$comment->id);

        $response->assertForbidden();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);
    }
}
