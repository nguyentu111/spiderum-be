<?php

use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\MailController;
use App\Http\Controllers\Auth\SignUpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::get('/sign-up', [SignUpController::class, 'show'])->name('sign-up');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password');

    Route::get('/facebook/callback', [FacebookController::class, 'show'])->name('facebook-login');

    Route::get('/mail-send', [MailController::class, 'show'])->name('mail-send');
});
