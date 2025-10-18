<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Actions\DeleteArticleAction;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteArticleController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly DeleteArticleAction $deleteArticle,
    ) {
    }

    public function __invoke(Request $request, string $slug): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->findArticleOrFail($slug);

        $this->authorize('delete', $article);

        $this->deleteArticle->execute($article);

        return response()->noContent();
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
