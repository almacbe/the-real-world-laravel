<?php

namespace App\Http\Controllers\Api\Articles;

use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Comments\Actions\DeleteCommentAction;
use App\Domain\Comments\Repositories\CommentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteCommentController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articles,
        private readonly CommentRepositoryInterface $comments,
        private readonly DeleteCommentAction $deleteComment,
    ) {
    }

    public function __invoke(Request $request, string $slug, int $commentId): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $article = $this->findArticleOrFail($slug);
        $comment = $this->findCommentOrFail($article, $commentId);

        $this->authorize('delete', $comment);

        $this->deleteComment->execute($comment);

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

    private function findCommentOrFail(Article $article, int $commentId): Comment
    {
        $comment = $this->comments->findForArticle($article, $commentId);

        if (! $comment) {
            abort(404);
        }

        return $comment;
    }
}
