<?php

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('lists posts', function () {
    get(route('posts.index'))
        ->assertOk()
        ->assertViewIs('posts.index')
        ->assertViewHas('posts', fn (LengthAwarePaginator $posts) => true);
});
