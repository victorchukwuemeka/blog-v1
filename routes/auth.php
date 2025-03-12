<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Auth\GithubAuthCallbackController;
use App\Http\Controllers\Auth\GithubAuthRedirectController;

Route::view('/login', 'login')
    ->middleware(RedirectIfAuthenticated::class)
    ->name('login');

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::middleware(RedirectIfAuthenticated::class)->group(function () {
        Route::get('/redirect', GithubAuthRedirectController::class)
            ->name('redirect');

        Route::get('/callback', GithubAuthCallbackController::class)
            ->name('callback');
    });

    Route::post('/logout', LogoutController::class)
        ->middleware(Authenticate::class)
        ->name('logout');
});
