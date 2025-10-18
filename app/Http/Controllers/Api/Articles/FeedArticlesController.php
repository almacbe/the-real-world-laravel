<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\FeedArticlesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\FeedArticlesRequest;
use App\Http\Resources\ArticleListResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FeedArticlesController extends Controller
{
    public function __construct(private readonly FeedArticlesAction $feedArticles)
    {
    }

    public function __invoke(FeedArticlesRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $result = $this->feedArticles->execute($user, $request->payload());

        return ArticleListResource::make($result)->response();
    }
}
