<?php

namespace App\Http\Resources;

use App\Domain\Comments\DataTransferObjects\CommentList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CommentList $resource
 */
class CommentListResource extends JsonResource
{
    public function __construct(CommentList $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comments' => array_map(
                fn ($comment) => $this->formatComment($comment, $request),
                $this->resource->comments,
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatComment($comment, Request $request): array
    {
        $data = CommentResource::formatComment($comment, $request);

        if (! $this->resource->isAuthenticated) {
            $data['author']['following'] = false;
        }

        return $data;
    }
}
