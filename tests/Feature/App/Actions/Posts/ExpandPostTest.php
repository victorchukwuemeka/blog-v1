<?php

use App\Actions\Posts\ParsePost;
use App\Actions\Posts\ExpandPost;
use Illuminate\Support\Collection;

it('adds categories to a post', function () {
    $post = app(ParsePost::class)->parse(resource_path('markdown/posts/laravel-best-practices.md'));

    $post = app(ExpandPost::class)->expand($post);

    expect($post['categories'])->toBeInstanceOf(Collection::class);
});

it('adds comments count to a post', function () {
    $post = app(ParsePost::class)->parse(resource_path('markdown/posts/laravel-best-practices.md'));

    $post = app(ExpandPost::class)->expand($post);

    expect($post['comments_count'])->toBeInt();
});
