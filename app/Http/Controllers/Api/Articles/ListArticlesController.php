<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\ListArticlesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\ListArticlesRequest;
use App\Http\Resources\ArticleListResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ListArticlesController extends Controller
{
    public function __construct(private readonly ListArticlesAction $listArticles)
    {
    }

    public function __invoke(ListArticlesRequest $request): JsonResponse
    {
        $viewer = $request->user();

        if ($viewer !== null && ! $viewer instanceof User) {
            abort(401);
        }

        $result = $this->listArticles->execute(
            $viewer instanceof User ? $viewer : null,
            $request->payload()
        );

        return ArticleListResource::make($result)->response();
    }
}
