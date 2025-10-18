<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\DataTransferObjects\ArticleData;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Articles\Services\ArticleDataFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnfavoriteArticleAction
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ArticleDataFactory $articleDataFactory,
    ) {
    }

    public function execute(User $user, string $slug): ArticleData
    {
        $article = $this->articles->findBySlug($slug);

        if (! $article) {
            throw new ModelNotFoundException();
        }

        $this->articles->unfavorite($article, $user);

        $article->refresh();

        return $this->articleDataFactory->make($article, $user);
    }
}
