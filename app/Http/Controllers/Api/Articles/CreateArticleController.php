<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\CreateArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CreateArticleController extends Controller
{
    public function __construct(private readonly CreateArticleAction $createArticle)
    {
    }

    public function __invoke(CreateArticleRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->createArticle->execute($user, $request->payload());

        return ArticleResource::make($article)
            ->response()
            ->setStatusCode(201);
    }
}
