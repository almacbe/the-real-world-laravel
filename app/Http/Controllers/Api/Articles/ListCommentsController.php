<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Comments\Actions\ListCommentsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentListResource;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class ListCommentsController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly ListCommentsAction $listComments,
        private readonly JWTAuth $jwtAuth,
    ) {
    }

    public function __invoke(Request $request, string $slug): JsonResponse
    {
        $viewer = $this->resolveViewer($request);

        $article = $this->findArticleOrFail($slug);

        $comments = $this->listComments->execute($article, $viewer instanceof User ? $viewer : null);

        return CommentListResource::make($comments)->response();
    }

    private function resolveViewer(Request $request): ?User
    {
        $token = $request->bearerToken();

        if (! $token) {
            return null;
        }

        try {
            return $this->jwtAuth->setToken($token)->authenticate();
        } catch (JWTException $exception) {
            throw $exception;
        }
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
