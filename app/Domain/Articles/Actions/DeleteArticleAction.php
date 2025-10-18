<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Models\Article;

class DeleteArticleAction
{
    public function __construct(private readonly ArticleRepositoryInterface $articles)
    {
    }

    public function execute(Article $article): void
    {
        $this->articles->delete($article);
    }
}
