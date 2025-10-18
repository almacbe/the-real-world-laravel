<?php

namespace App\Http\Controllers\Api\Tags;

use App\Domain\Tags\Repositories\TagRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagListResource;
use Illuminate\Http\JsonResponse;

class ListTagsController extends Controller
{
    public function __construct(private readonly TagRepositoryInterface $tags)
    {
    }

    public function __invoke(): JsonResponse
    {
        return TagListResource::make($this->tags->all())->response();
    }
}
