<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UploadImageController;
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
        Route::post('/{slug}', [CategoryController::class, 'update']);
        Route::delete('/{slug}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('/tags')->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::post('/', [TagController::class, 'store']);
        Route::post('/{slug}', [TagController::class, 'update']);
        Route::delete('/{slug}', [TagController::class, 'destroy']);
    });

    Route::prefix('/series')->group(function () {
        Route::get('/', [SeriesController::class, 'index']);
        Route::post('/', [SeriesController::class, 'store']);
        Route::post('/{slug}', [SeriesController::class, 'update']);
        Route::delete('/{slug}', [SeriesController::class, 'destroy']);
        Route::post('/add/{slugPost}/into/{slugSeries}', [SeriesController::class, 'addPostToSeries']);
        Route::post('/remove/{slugPost}/in/{slugSeries}', [SeriesController::class, 'removePostInSeries']);
    });

    Route::prefix('/posts')->group(function () {
        Route::get('/', [PostController::class, 'getUserPosts']);
        Route::get('/{slug}', [PostController::class, 'getPost']);
        Route::post('/', [PostController::class, 'store']);
        Route::patch('/show/{slug}', [PostController::class, 'showPost']);
        Route::patch('/hide/{slug}', [PostController::class, 'hidePost']);
        Route::patch('/like/{slug}', [PostController::class, 'likePost']);
        Route::patch('/unlike/{slug}', [PostController::class, 'unlikePost']);
        Route::patch('/count-view/{slug}', [PostController::class, 'countView']);
        Route::post('/save/{slug}', [PostController::class, 'savePost']);
        Route::post('/unsave/{slug}', [PostController::class, 'unsavePost']);
        Route::delete('/{slug}', [PostController::class, 'destroy']);
    });

    Route::post('/upload-image', [UploadImageController::class, '__invoke']);
});

