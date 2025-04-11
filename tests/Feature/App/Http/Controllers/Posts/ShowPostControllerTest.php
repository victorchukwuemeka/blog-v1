<?php

use function Pest\Laravel\get;

it('shows a post', function () {
    get(route('posts.show', 'laravel-best-practices'))
        ->assertOk()
        ->assertViewIs('posts.show');
});

it('throws a 404 if the post does not exist', function () {
    get(route('posts.show', 'non-existent-post'))
        ->assertNotFound();
});
