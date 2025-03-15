<?php

use function Pest\Laravel\get;

it('shows a post', function () {
    get(route('posts.show', 'laravel-best-practices'))
        ->assertOk()
        ->assertViewIs('posts.show')
        ->assertViewHas('post', function (array $post) {
            expect($post['slug'])->toBe('laravel-best-practices');

            return true;
        })
        ->assertViewHas('readTime', function (int $readTime) {
            expect($readTime)->toBeGreaterThan(0);

            return true;
        });
});

it('throws a 404 if the post does not exist', function () {
    get(route('posts.show', 'non-existent-post'))
        ->assertNotFound();
});
