<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFollowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['cookie.token', 'auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'show']);
    Route::get('/followers', [UserFollowerController::class, 'getFollowers']);
    Route::post('/follow', [UserFollowerController::class, 'follow']);
    Route::post('/unfollow', [UserFollowerController::class, 'unfollow']);
});

