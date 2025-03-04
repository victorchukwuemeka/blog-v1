<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\GithubAuthCallbackController;
use App\Http\Controllers\GithubAuthRedirectController;

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::get('/redirect', GithubAuthRedirectController::class)->name('redirect');

    Route::get('/callback', GithubAuthCallbackController::class)->name('callback');

    Route::post('/logout', LogoutController::class)->name('logout');
});
