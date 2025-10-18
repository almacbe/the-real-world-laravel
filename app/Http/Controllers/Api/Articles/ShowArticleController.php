<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\ShowArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowArticleController extends Controller
{
    public function __construct(private readonly ShowArticleAction $showArticle)
    {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $viewer = $request->user();

        if ($viewer !== null && ! $viewer instanceof User) {
            abort(401);
        }

        $article = $this->showArticle->execute($slug, $viewer instanceof User ? $viewer : null);

        return ArticleResource::make($article)->response();
    }
}
