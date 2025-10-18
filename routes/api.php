<?php

use App\Http\Controllers\Api\Auth\LoginUserController;
use App\Http\Controllers\Api\Auth\RegisterUserController;
use App\Http\Controllers\Api\User\CurrentUserController;
use App\Http\Controllers\Api\User\UpdateUserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', RegisterUserController::class);
Route::post('/users/login', LoginUserController::class);

Route::middleware('auth.jwt')->group(function (): void {
    Route::get('/user', CurrentUserController::class);
    Route::put('/user', UpdateUserController::class);
});
