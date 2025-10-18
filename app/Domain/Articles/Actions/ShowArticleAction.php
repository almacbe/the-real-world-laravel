<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\DataTransferObjects\ArticleData;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Articles\Services\ArticleDataFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowArticleAction
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ArticleDataFactory $articleDataFactory,
    ) {
    }

    public function execute(string $slug, ?User $viewer): ArticleData
    {
        $article = $this->articles->findBySlug($slug);

        if (! $article) {
            throw new ModelNotFoundException();
        }

        return $this->articleDataFactory->make($article, $viewer);
    }
}
