<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Links\ShowLinkController;
use App\Http\Controllers\Posts\ShowPostController;
use App\Http\Controllers\Links\ListLinksController;
use App\Http\Controllers\Posts\ListPostsController;

Route::get('/', HomeController::class)->name('home');

Route::get('/blog', ListPostsController::class)->name('posts.index');

Route::get('/links', ListLinksController::class)->name('links.index');
Route::get('/links/{link:slug}', ShowLinkController::class)->name('links.show');

Route::get('/{slug}', ShowPostController::class)->name('posts.show');
