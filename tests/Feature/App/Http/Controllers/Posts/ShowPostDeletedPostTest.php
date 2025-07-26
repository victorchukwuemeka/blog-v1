<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('returns 410 gone when the post is soft deleted', function () {
    $post = Post::factory()->create();

    $post->delete();

    get(route('posts.show', $post))
        ->assertStatus(410);
});
