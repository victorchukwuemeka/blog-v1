<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\GithubAuthCallbackController;
use App\Http\Controllers\Auth\GithubAuthRedirectController;

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::get('/redirect', GithubAuthRedirectController::class)->name('redirect');

    Route::get('/callback', GithubAuthCallbackController::class)->name('callback');

    Route::post('/logout', LogoutController::class)->name('logout');
});
