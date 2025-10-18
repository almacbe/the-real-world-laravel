<?php

namespace App\Domain\Articles\Services;

use App\Domain\Articles\DataTransferObjects\ArticleData;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Profiles\Actions\GetProfileAction;
use App\Domain\Profiles\DataTransferObjects\ProfileData;
use App\Models\Article;
use App\Models\User;

class ArticleDataFactory
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly GetProfileAction $getProfile,
    ) {
    }

    public function make(Article $article, ?User $viewer): ArticleData
    {
        $article->loadMissing(['tags', 'author']);

        $profile = $this->buildAuthorProfile($article, $viewer);

        return new ArticleData(
            article: $article,
            author: $profile,
            favorited: $this->articles->isFavoritedBy($article, $viewer),
            favoritesCount: $this->articles->favoritesCount($article),
            tagList: $this->articles->retrieveTagList($article),
        );
    }

    private function buildAuthorProfile(Article $article, ?User $viewer): ProfileData
    {
        return $this->getProfile->execute($article->author->username, $viewer);
    }
}
