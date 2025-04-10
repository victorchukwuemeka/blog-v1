<?php

use App\Actions\Posts\ExpandPost;
use Illuminate\Support\Collection;
use App\Actions\Posts\ParseMarkdownFile;

it('adds categories to a post', function () {
    $post = app(ParseMarkdownFile::class)->parse(resource_path('markdown/posts/laravel-best-practices.md'));

    $post = app(ExpandPost::class)->expand($post);

    expect($post['categories'])->toBeInstanceOf(Collection::class);
});

it('adds comments count to a post', function () {
    $post = app(ParseMarkdownFile::class)->parse(resource_path('markdown/posts/laravel-best-practices.md'));

    $post = app(ExpandPost::class)->expand($post);

    expect($post['comments_count'])->toBeInt();
});
