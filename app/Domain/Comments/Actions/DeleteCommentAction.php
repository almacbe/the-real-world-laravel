<?php

namespace App\Domain\Comments\Actions;

use App\Domain\Comments\Repositories\CommentRepositoryInterface;
use App\Models\Comment;

class DeleteCommentAction
{
    public function __construct(private readonly CommentRepositoryInterface $comments)
    {
    }

    public function execute(Comment $comment): void
    {
        $this->comments->delete($comment);
    }
}
