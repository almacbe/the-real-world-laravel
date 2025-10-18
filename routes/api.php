<?php

use App\Http\Controllers\Api\Auth\LoginUserController;
use App\Http\Controllers\Api\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', RegisterUserController::class);
Route::post('/users/login', LoginUserController::class);
