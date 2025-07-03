<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('lists the latest 50 posts and shows the description instead of the content', function () {
    $post = Post::factory()->create();

    get(route('feeds.main'))
        ->assertOk()
        ->assertSee($post->slug)
        ->assertSee($post->title, escape: false)
        ->assertSee($post->description, escape: false)
        ->assertSee(route('posts.show', $post))
        ->assertSee($post->user->name, escape: false)
        ->assertDontSee($post->content);
});
