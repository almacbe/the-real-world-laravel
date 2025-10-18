<?php

namespace App\Domain\Comments\Services;

use App\Domain\Comments\DataTransferObjects\CommentData;
use App\Domain\Profiles\Actions\GetProfileAction;
use App\Models\Comment;
use App\Models\User;

class CommentDataFactory
{
    public function __construct(private readonly GetProfileAction $getProfile)
    {
    }

    public function make(Comment $comment, ?User $viewer): CommentData
    {
        $comment->loadMissing('author');

        $author = $comment->author;
        $following = false;

        if ($viewer instanceof User) {
            $profile = $this->getProfile->execute($author->username, $viewer);
            $following = $profile->following;
        }

        return new CommentData(
            $comment,
            new \App\Domain\Profiles\DataTransferObjects\ProfileData($author, $following)
        );
    }
}
