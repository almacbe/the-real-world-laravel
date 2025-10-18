<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\UnfavoriteArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnfavoriteArticleController extends Controller
{
    public function __construct(private readonly UnfavoriteArticleAction $unfavoriteArticle)
    {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->unfavoriteArticle->execute($user, $slug);

        return ArticleResource::make($article)->response();
    }
}
