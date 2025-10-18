<?php

namespace App\Domain\Articles\Services;

use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\Str;

class SlugGenerator
{
    public function __construct(private readonly ArticleRepositoryInterface $articles)
    {
    }

    public function generate(string $title, ?int $ignoreArticleId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = Str::lower(Str::random(8));
        }

        $slug = $base;
        $suffix = 1;

        while ($this->articles->slugExists($slug, $ignoreArticleId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
