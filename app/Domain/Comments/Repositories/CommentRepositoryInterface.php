<?php

namespace App\Domain\Comments\Repositories;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Collection;

interface CommentRepositoryInterface
{
    /**
     * @param  array{article_id:int,author_id:int,body:string}  $attributes
     */
    public function create(array $attributes): Comment;

    public function find(int $id): ?Comment;

    public function findForArticle(Article $article, int $commentId): ?Comment;

    /**
     * @return Collection<int, Comment>
     */
    public function listForArticle(Article $article): Collection;

    public function delete(Comment $comment): void;
}
