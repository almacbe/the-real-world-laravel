<?php

namespace App\Domain\Articles\Repositories;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function create(array $attributes): Article
    {
        return Article::query()->create($attributes);
    }

    public function update(Article $article, array $attributes): Article
    {
        $article->fill($attributes);
        $article->save();

        return $article->refresh();
    }

    public function delete(Article $article): void
    {
        $article->delete();
    }

    public function findBySlug(string $slug): ?Article
    {
        return Article::query()
            ->where('slug', $slug)
            ->first();
    }

    public function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Article::query()
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists();
    }

    public function syncTags(Article $article, array $tagNames): void
    {
        $tagIds = Collection::make($tagNames)
            ->filter(fn ($name) => is_string($name) && $name !== '')
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique()
            ->map(function (string $name) {
                return Tag::query()->firstOrCreate(['name' => $name])->getKey();
            })
            ->values()
            ->all();

        $article->tags()->sync($tagIds);
    }

    public function retrieveTagList(Article $article): array
    {
        return $article->tags
            ->pluck('name')
            ->sort()
            ->values()
            ->all();
    }

    public function isFavoritedBy(Article $article, ?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $article->favoritedBy()->whereKey($user->getKey())->exists();
    }

    public function favoritesCount(Article $article): int
    {
        return $article->favoritedBy()->count();
    }
}
