<?php

namespace App\Domain\Articles\Actions;

use App\Domain\Articles\DataTransferObjects\ArticleData;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Articles\Services\ArticleDataFactory;
use App\Domain\Articles\Services\SlugGenerator;
use App\Models\User;

class CreateArticleAction
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly SlugGenerator $slugGenerator,
        private readonly ArticleDataFactory $articleDataFactory,
    ) {
    }

    /**
     * @param  array{title:string,description:string,body:string,tagList?:array<int, string>}  $payload
     */
    public function execute(User $author, array $payload): ArticleData
    {
        $slug = $this->slugGenerator->generate($payload['title']);

        $article = $this->articles->create([
            'author_id' => $author->getKey(),
            'title' => $payload['title'],
            'slug' => $slug,
            'description' => $payload['description'],
            'body' => $payload['body'],
        ]);

        $this->articles->syncTags($article, $payload['tagList'] ?? []);

        return $this->articleDataFactory->make($article, $author);
    }
}
