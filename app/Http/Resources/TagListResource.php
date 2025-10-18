<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string[] $resource
 */
class TagListResource extends JsonResource
{
    /**
     * @param  string[]  $resource
     */
    public function __construct(array $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tags' => $this->resource,
        ];
    }
}
