<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\DataTransferObjects\ArticleData;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Articles\Services\ArticleDataFactory;
use App\Domain\Articles\Services\SlugGenerator;
use App\Models\Article;
use App\Models\User;

class UpdateArticleAction
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly SlugGenerator $slugGenerator,
        private readonly ArticleDataFactory $articleDataFactory,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(Article $article, array $payload, User $viewer): ArticleData
    {
        $attributes = [];

        if (array_key_exists('title', $payload)) {
            $attributes['title'] = $payload['title'];
            $attributes['slug'] = $this->slugGenerator->generate($payload['title'], $article->getKey());
        }

        if (array_key_exists('description', $payload)) {
            $attributes['description'] = $payload['description'];
        }

        if (array_key_exists('body', $payload)) {
            $attributes['body'] = $payload['body'];
        }

        if ($attributes !== []) {
            $article = $this->articles->update($article, $attributes);
        }

        return $this->articleDataFactory->make($article, $viewer);
    }
}
