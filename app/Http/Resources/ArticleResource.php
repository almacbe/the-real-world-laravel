<?php

namespace App\Http\Resources;

use App\Domain\Articles\DataTransferObjects\ArticleData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ArticleData $resource
 */
class ArticleResource extends JsonResource
{
    public function __construct(ArticleData $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $article = $this->resource->article;
        $author = $this->resource->author;

        return [
            'article' => [
                'slug' => $article->slug,
                'title' => $article->title,
                'description' => $article->description,
                'body' => $article->body,
                'tagList' => $this->resource->tagList,
                'createdAt' => $article->created_at?->toISOString(),
                'updatedAt' => $article->updated_at?->toISOString(),
                'favorited' => $this->resource->favorited,
                'favoritesCount' => $this->resource->favoritesCount,
                'author' => [
                    'username' => $author->user->username,
                    'bio' => $author->user->bio,
                    'image' => $author->user->image,
                    'following' => $author->following,
                ],
            ],
        ];
    }
}
