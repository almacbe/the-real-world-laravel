<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\DataTransferObjects\ArticleList;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Articles\Services\ArticleDataFactory;
use App\Models\User;

class ListArticlesAction
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ArticleDataFactory $articleDataFactory,
    ) {
    }

    /**
     * @param  array{tag:?string,author:?string,favorited:?string,limit:int,offset:int}  $filters
     */
    public function execute(?User $viewer, array $filters): ArticleList
    {
        $result = $this->articles->listArticles(
            $filters['tag'] ?? null,
            $filters['author'] ?? null,
            $filters['favorited'] ?? null,
            $filters['limit'],
            $filters['offset'],
        );

        $articleData = $result['articles']->map(
            fn ($article) => $this->articleDataFactory->make($article, $viewer)
        )->all();

        return new ArticleList($articleData, $result['count']);
    }
}
