<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function update(User $user, Article $article): bool
    {
        return $article->author_id === $user->getKey();
    }

    public function delete(User $user, Article $article): bool
    {
        return $article->author_id === $user->getKey();
    }
}
