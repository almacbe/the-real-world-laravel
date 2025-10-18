<?php

namespace App\Domain\Comments\Repositories;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Collection;

class CommentRepository implements CommentRepositoryInterface
{
    public function create(array $attributes): Comment
    {
        return Comment::query()->create($attributes);
    }

    public function find(int $id): ?Comment
    {
        return Comment::query()->find($id);
    }

    public function findForArticle(Article $article, int $commentId): ?Comment
    {
        return Comment::query()
            ->where('article_id', $article->getKey())
            ->whereKey($commentId)
            ->first();
    }

    public function listForArticle(Article $article): Collection
    {
        return Comment::query()
            ->with('author')
            ->where('article_id', $article->getKey())
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
