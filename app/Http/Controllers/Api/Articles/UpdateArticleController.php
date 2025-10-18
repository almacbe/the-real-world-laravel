<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\UpdateArticleAction;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UpdateArticleController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly UpdateArticleAction $updateArticle,
    ) {
    }

    public function __invoke(UpdateArticleRequest $request, string $slug): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->findArticleOrFail($slug);

        $this->authorize('update', $article);

        $updatedArticle = $this->updateArticle->execute($article, $request->payload(), $user);

        return ArticleResource::make($updatedArticle)->response();
    }

    private function findArticleOrFail(string $slug): Article
    {
        $article = $this->articles->findBySlug($slug);

        if (! $article) {
            abort(404);
        }

        return $article;
    }
}
