<?php

namespace App\Domain\Articles\DataTransferObjects;

use App\Domain\Profiles\DataTransferObjects\ProfileData;
use App\Models\Article;

class ArticleData
{
    /**
     * @param  string[]  $tagList
     */
    public function __construct(
        public readonly Article $article,
        public readonly ProfileData $author,
        public readonly bool $favorited,
        public readonly int $favoritesCount,
        public readonly array $tagList,
    ) {
    }
}
