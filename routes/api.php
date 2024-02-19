<?php

use App\Http\Controllers\CategoryController;
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
    Route::get('/followings', [UserFollowerController::class, 'getFollowings']);
    Route::post('/follow', [UserFollowerController::class, 'follow']);
    Route::post('/unfollow', [UserFollowerController::class, 'unfollow']);

    Route::put('/update-profile', [UserController::class, 'update']);

    Route::prefix('/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::post('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});

