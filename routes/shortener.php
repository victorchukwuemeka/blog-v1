<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrls\ShowShortUrlController;

Route::domain(config('app.url_shortener_domain'))
    ->group(function () {
        Route::get('/{shortUrl:code}', ShowShortUrlController::class)
            ->name('shortUrl.show');
    });
