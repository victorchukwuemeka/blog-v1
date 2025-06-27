<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('lists the latest 50 posts and shows the description instead of the content', function () {
    $post = Post::factory()->create();

    get(route('feeds.main'))
        ->assertOk()
        ->assertSee($post->slug)
        ->assertSee($post->title)
        ->assertSee($post->description)
        ->assertSee(route('posts.show', $post))
        ->assertSee($post->user->name)
        ->assertDontSee($post->content);
});
