<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('shows a post', function () {
    $post = Post::factory()->create();

    get(route('posts.show', $post))
        ->assertOk()
        ->assertViewIs('posts.show')
        ->assertViewHas('post', $post);
});

it('throws a 404 if the post does not exist', function () {
    get(route('posts.show', 'non-existent-post'))
        ->assertNotFound();
});
