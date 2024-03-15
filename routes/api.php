<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewfeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostDraftController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UploadImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFollowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', fn(Request $request)=>$request->a);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', [LoginController::class, 'store']);
Route::post('/register', [SignUpController::class, 'store']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
Route::get('/new-feed',[NewfeedController::class,'getNewfeed']);
Route::get('/top-view',[NewfeedController::class,'getTopView']);
Route::get('/categories',[CategoryController::class,'index']);
Route::get('/posts', [PostController::class, 'getPosts']);
Route::get('/posts/{slug}', [PostController::class, 'getPost']);
Route::patch('/posts/count-view/{slug}', [PostController::class, 'countView']);
Route::get('/comments',[CommentController::class,'getComments']);
Route::get('/new-top-writer',[NewfeedController::class,'getNewTopWriter']);
Route::get('/old-but-gold-post',[NewfeedController::class,'getOldButGoldPost']);
// Route::get('/categories/{slug}',[CategoryController::class ,''];
Route::prefix('auth')->group(function () {
    Route::prefix('/users')->group(function () {
        Route::post('get-email-by-token', [UserController::class, 'getEmailByToken']);
        Route::post('store', [UserController::class, 'store'])->name('store-user');
    });
});



Route::get('/users/{user}',[UserController::class,'getUser']);

Route::prefix('/series')->group(function () {
    Route::get('/', [SeriesController::class, 'index']);
    Route::get('/{slug}', [SeriesController::class, 'show']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LogoutController::class, '__invoke']);
    Route::get('/profile', [UserController::class, 'show']);
    Route::get('/followers', [UserFollowerController::class, 'getFollowers']);
    Route::get('/followings', [UserFollowerController::class, 'getFollowings']);
    Route::post('/follow', [UserFollowerController::class, 'follow']);
    Route::post('/unfollow', [UserFollowerController::class, 'unfollow']);

    Route::put('/update-profile', [UserController::class, 'update']);

    Route::prefix('/categories')->group(function () {
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
        // Route::get('/', [SeriesController::class, 'index']);
        // Route::get('/{slug}', [SeriesController::class, 'show']);
        Route::post('/', [SeriesController::class, 'store']);
        Route::post('/{slug}', [SeriesController::class, 'update']);
        Route::delete('/{slug}', [SeriesController::class, 'destroy']);
        Route::post('/add/{slugPost}/to/{slugSeries}', [SeriesController::class, 'addPostToSeries']);
        Route::post('/remove/{slugPost}/in/{slugSeries}', [SeriesController::class, 'removePostInSeries']);
    });

  
    Route::get('/saved-posts', [PostController::class, 'getSavePosts']);
    Route::prefix('/posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::patch('/show/{slug}', [PostController::class, 'showPost']);
        Route::patch('/hide/{slug}', [PostController::class, 'hidePost']);
        Route::patch('/vote/{slug}', [PostController::class, 'vote']);
        Route::post('/save/{slug}', [PostController::class, 'savePost']);
        Route::post('/unsave/{slug}', [PostController::class, 'unsavePost']);
        Route::delete('/{slug}', [PostController::class, 'destroy']);
       
    });
    Route::prefix('/drafts')->group(function () {
        Route::get('/',[PostDraftController::class,'index']);
        Route::get('/{draft}',[PostDraftController::class,'show']);
        Route::post('/',[PostDraftController::class,'store']);
        Route::delete('/{draft}',[PostDraftController::class,'delete']);
    });
    Route::prefix('/comments')->group(function(){
        Route::post('/',[ CommentController::class,'comment']);
        Route::delete('/{comment}',[ CommentController::class,'delete']);
        Route::post('/vote/{comment}',[ CommentController::class,'vote']);
    });
    Route::post('/upload-image', [UploadImageController::class, '__invoke']);
});

