<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('shows a given author with their posts and links', function () {
    Post::factory(3)->create();

    Link::factory(3)->create();

    $user = User::factory()
        ->hasPosts(3, ['published_at' => now()])
        ->hasLinks(3, ['is_approved' => now()])
        ->create();

    get(route('authors.show', $user))
        ->assertOk()
        ->assertViewIs('authors.show')
        ->assertViewHas('author', $user)
        ->assertViewHas('posts', function (LengthAwarePaginator $posts) {
            expect($posts->count())->toBe(3);

            return true;
        })
        ->assertViewHas('links', function (LengthAwarePaginator $links) {
            expect($links->count())->toBe(3);

            return true;
        });
});
