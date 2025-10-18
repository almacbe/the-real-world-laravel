<?php

namespace App\Domain\Articles\Repositories;

use App\Models\Article;
use App\Models\User;

interface ArticleRepositoryInterface
{
    /**
     * @param  array{author_id:int,title:string,slug:string,description:string,body:string}  $attributes
     */
    public function create(array $attributes): Article;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Article $article, array $attributes): Article;

    public function delete(Article $article): void;

    public function findBySlug(string $slug): ?Article;

    public function slugExists(string $slug, ?int $ignoreId = null): bool;

    /**
     * @param  string[]  $tagNames
     */
    public function syncTags(Article $article, array $tagNames): void;

    /**
     * @return string[]
     */
    public function retrieveTagList(Article $article): array;

    public function isFavoritedBy(Article $article, ?User $user): bool;

    public function favoritesCount(Article $article): int;
}
