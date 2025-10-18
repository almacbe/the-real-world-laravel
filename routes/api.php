<?php

use App\Http\Controllers\Api\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', RegisterUserController::class);
