<?php

use App\Http\Controllers\Api\Articles\CreateArticleController;
use App\Http\Controllers\Api\Articles\DeleteArticleController;
use App\Http\Controllers\Api\Articles\ShowArticleController;
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
    Route::get('/profiles/{username}', ShowProfileController::class);
    Route::get('/articles/{slug}', ShowArticleController::class);
});

Route::middleware('auth.jwt')->group(function (): void {
    Route::get('/user', CurrentUserController::class);
    Route::put('/user', UpdateUserController::class);
    Route::post('/profiles/{username}/follow', FollowProfileController::class);
    Route::delete('/profiles/{username}/follow', UnfollowProfileController::class);

    Route::post('/articles', CreateArticleController::class);
    Route::put('/articles/{slug}', UpdateArticleController::class);
    Route::delete('/articles/{slug}', DeleteArticleController::class);
});
