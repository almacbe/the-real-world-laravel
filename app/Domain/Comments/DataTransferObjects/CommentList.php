<?php

namespace App\Domain\Comments\DataTransferObjects;

/**
 * @phpstan-type CommentDataArray array<int, CommentData>
 */
class CommentList
{
    /**
     * @param  CommentData[]  $comments
     */
    public function __construct(
        public readonly array $comments,
        public readonly bool $isAuthenticated,
    ) {
    }
}
