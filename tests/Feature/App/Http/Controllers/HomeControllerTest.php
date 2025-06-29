<?php

use App\Models\Post;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

it('renders with popular and latest posts', function () {
    Post::factory(15)->create(['sessions_count' => 0]);
    Post::factory(15)->create(['sessions_count' => random_int(1, 1000)]);

    get(route('home'))
        ->assertOk()
        ->assertViewIs('home')
        ->assertViewHas('popular', fn (Collection $popular) => 12 === $popular->count())
        ->assertViewHas('latest', fn (Collection $latest) => 12 === $latest->count());
});

it('does not show popular posts if there are no sessions', function () {
    Post::factory(15)->create(['sessions_count' => 0]);

    get(route('home'))
        ->assertViewHas('popular', fn (Collection $popular) => $popular->isEmpty());
});
