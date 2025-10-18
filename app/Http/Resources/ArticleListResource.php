<?php

namespace App\Http\Resources;

use App\Domain\Articles\DataTransferObjects\ArticleList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ArticleList $resource
 */
class ArticleListResource extends JsonResource
{
    public function __construct(ArticleList $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'articles' => array_map(
                static fn ($article) => ArticleResource::formatArticle($article),
                $this->resource->articles,
            ),
            'articlesCount' => $this->resource->count,
        ];
    }
}
