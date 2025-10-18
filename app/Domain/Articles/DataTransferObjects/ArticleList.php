<?php

namespace App\Domain\Articles\DataTransferObjects;

/**
 * @phpstan-type ArticleDataArray array<int, ArticleData>
 */
class ArticleList
{
    /**
     * @param  ArticleData[]  $articles
     */
    public function __construct(
        public readonly array $articles,
        public readonly int $count,
    ) {
    }
}

