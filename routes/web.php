<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Links\ShowLinkController;
use App\Http\Controllers\Posts\ShowPostController;
use App\Http\Controllers\Links\ListLinksController;
use App\Http\Controllers\Posts\ListPostsController;
use App\Http\Controllers\Links\CreateLinkController;
use App\Http\Controllers\Merchants\ShowMerchantController;

Route::get('/', HomeController::class)->name('home');

Route::get('/blog', ListPostsController::class)->name('posts.index');

Route::get('/links', ListLinksController::class)->name('links.index');
// This route needs to be above the links.show route to take precedence.
Route::get('/links/create', CreateLinkController::class)->name('links.create');
Route::get('/links/{link:slug}', ShowLinkController::class)->name('links.show');

Route::get('/merchants/{slug}', ShowMerchantController::class)->name('merchants.show');

// This route needs to be the last one so all others take precedence.
Route::get('/{slug}', ShowPostController::class)->name('posts.show');
