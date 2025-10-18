<?php

namespace App\Providers;

use App\Domain\Articles\Repositories\ArticleRepository;
use App\Domain\Articles\Repositories\ArticleRepositoryInterface;
use App\Domain\Comments\Repositories\CommentRepository;
use App\Domain\Comments\Repositories\CommentRepositoryInterface;
use App\Domain\Users\Repositories\UserRepository;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
