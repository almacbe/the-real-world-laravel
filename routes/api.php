<?php

use App\Http\Controllers\Api\Articles\CreateArticleController;
use App\Http\Controllers\Api\Articles\DeleteArticleController;
use App\Http\Controllers\Api\Articles\FavoriteArticleController;
use App\Http\Controllers\Api\Articles\FeedArticlesController;
use App\Http\Controllers\Api\Articles\ListArticlesController;
use App\Http\Controllers\Api\Articles\ListCommentsController;
use App\Http\Controllers\Api\Articles\AddCommentController;
use App\Http\Controllers\Api\Articles\DeleteCommentController;
use App\Http\Controllers\Api\Articles\ShowArticleController;
use App\Http\Controllers\Api\Articles\UnfavoriteArticleController;
use App\Http\Controllers\Api\Articles\UpdateArticleController;
use App\Http\Controllers\Api\Auth\LoginUserController;
use App\Http\Controllers\Api\Auth\RegisterUserController;
use App\Http\Controllers\Api\Profile\FollowProfileController;
use App\Http\Controllers\Api\Profile\ShowProfileController;
use App\Http\Controllers\Api\Profile\UnfollowProfileController;
use App\Http\Controllers\Api\User\CurrentUserController;
use App\Http\Controllers\Api\User\UpdateUserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', RegisterUserController::class);
Route::post('/users/login', LoginUserController::class);

Route::middleware('auth.jwt.optional')->group(function (): void {
    Route::get('/articles', ListArticlesController::class);
    Route::get('/profiles/{username}', ShowProfileController::class);
    Route::get('/articles/{slug}/comments', ListCommentsController::class);
});

Route::middleware('auth.jwt')->group(function (): void {
    Route::get('/user', CurrentUserController::class);
    Route::put('/user', UpdateUserController::class);
    Route::post('/profiles/{username}/follow', FollowProfileController::class);
    Route::delete('/profiles/{username}/follow', UnfollowProfileController::class);

    Route::get('/articles/feed', FeedArticlesController::class);
    Route::post('/articles', CreateArticleController::class);
    Route::post('/articles/{slug}/favorite', FavoriteArticleController::class);
    Route::put('/articles/{slug}', UpdateArticleController::class);
    Route::delete('/articles/{slug}/favorite', UnfavoriteArticleController::class);
    Route::delete('/articles/{slug}', DeleteArticleController::class);

    Route::post('/articles/{slug}/comments', AddCommentController::class);
    Route::delete('/articles/{slug}/comments/{commentId}', DeleteCommentController::class);
});

Route::middleware('auth.jwt.optional')->get('/articles/{slug}', ShowArticleController::class);
