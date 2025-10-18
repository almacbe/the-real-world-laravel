<?php

namespace App\Domain\Comments\Actions;

use App\Domain\Comments\DataTransferObjects\CommentData;
use App\Domain\Comments\Repositories\CommentRepositoryInterface;
use App\Domain\Comments\Services\CommentDataFactory;
use App\Models\Article;
use App\Models\User;

class CreateCommentAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $comments,
        private readonly CommentDataFactory $commentDataFactory,
    ) {
    }

    /**
     * @param  array{body:string}  $payload
     */
    public function execute(Article $article, User $user, array $payload): CommentData
    {
        $comment = $this->comments->create([
            'article_id' => $article->getKey(),
            'author_id' => $user->getKey(),
            'body' => $payload['body'],
        ]);

        return $this->commentDataFactory->make($comment, $user);
    }
}
