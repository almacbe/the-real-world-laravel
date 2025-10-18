<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\DataTransferObjects\ArticleList;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Articles\Services\ArticleDataFactory;
use App\Models\User;

class FeedArticlesAction
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ArticleDataFactory $articleDataFactory,
    ) {
    }

    /**
     * @param  array{limit:int,offset:int}  $filters
     */
    public function execute(User $user, array $filters): ArticleList
    {
        $result = $this->articles->feedForUser(
            $user,
            $filters['limit'],
            $filters['offset'],
        );

        $articleData = $result['articles']->map(
            fn ($article) => $this->articleDataFactory->make($article, $user)
        )->all();

        return new ArticleList($articleData, $result['count']);
    }
}
