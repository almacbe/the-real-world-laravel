<?php

namespace App\Http\Resources;

use App\Domain\Comments\DataTransferObjects\CommentData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CommentData $resource
 */
class CommentResource extends JsonResource
{
    public function __construct(CommentData $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment' => self::formatComment($this->resource, $request),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function formatComment(CommentData $data, ?Request $request = null): array
    {
        $comment = $data->comment;
        $author = $data->author;
        $following = $author->following;

        return [
            'id' => $comment->getKey(),
            'createdAt' => $comment->created_at?->toISOString(),
            'updatedAt' => $comment->updated_at?->toISOString(),
            'body' => $comment->body,
            'author' => [
                'username' => $author->user->username,
                'bio' => $author->user->bio,
                'image' => $author->user->image,
                'following' => $following,
            ],
        ];
    }
}
