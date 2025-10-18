<?php

namespace App\Domain\Comments\Actions;

use App\Domain\Comments\DataTransferObjects\CommentList;
use App\Domain\Comments\Repositories\CommentRepositoryInterface;
use App\Domain\Comments\Services\CommentDataFactory;
use App\Models\Article;
use App\Models\User;

class ListCommentsAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $comments,
        private readonly CommentDataFactory $commentDataFactory,
    ) {
    }

    public function execute(Article $article, ?User $viewer): CommentList
    {
        $commentData = $this->comments->listForArticle($article)
            ->map(fn ($comment) => $this->commentDataFactory->make($comment, $viewer))
            ->all();

        return new CommentList($commentData, $viewer !== null);
    }
}
