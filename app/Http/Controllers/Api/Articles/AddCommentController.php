<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Comments\Actions\CreateCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Articles\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AddCommentController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly CreateCommentAction $createComment,
    ) {
    }

    public function __invoke(CreateCommentRequest $request, string $slug): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->findArticleOrFail($slug);

        $comment = $this->createComment->execute($article, $user, $request->payload());

        return CommentResource::make($comment)
            ->response()
            ->setStatusCode(201);
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
