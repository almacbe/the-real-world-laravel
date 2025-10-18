<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\FavoriteArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteArticleController extends Controller
{
    public function __construct(private readonly FavoriteArticleAction $favoriteArticle)
    {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->favoriteArticle->execute($user, $slug);

        return ArticleResource::make($article)->response();
    }
}
