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

    /**
     * @return array{articles: \Illuminate\Support\Collection<int, Article>, count: int}
     */
    public function listArticles(?string $tag, ?string $author, ?string $favoritedBy, int $limit, int $offset): array;

    /**
     * @return array{articles: \Illuminate\Support\Collection<int, Article>, count: int}
     */
    public function feedForUser(User $user, int $limit, int $offset): array;

    public function favorite(Article $article, User $user): void;

    public function unfavorite(Article $article, User $user): void;
}
