<?php

namespace App\Domain\Comments\DataTransferObjects;

use App\Domain\Profiles\DataTransferObjects\ProfileData;
use App\Models\Comment;

class CommentData
{
    public function __construct(
        public readonly Comment $comment,
        public readonly ProfileData $author,
    ) {
    }
}
