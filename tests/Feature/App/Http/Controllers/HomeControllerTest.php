<?php

use App\Models\Post;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

it('renders', function () {
    Post::factory(15)->create();

    get(route('home'))
        ->assertOk()
        ->assertViewIs('home')
        ->assertViewHas('latest', fn (Collection $latest) => 12 === $latest->count());
});
