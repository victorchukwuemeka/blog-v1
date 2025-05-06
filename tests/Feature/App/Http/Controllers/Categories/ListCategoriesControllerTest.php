<?php

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

it('lists posts', function () {
    get(route('categories.index'))
        ->assertOk()
        ->assertViewIs('categories.index')
        ->assertViewHas('categories', fn (Collection $categories) => true);
});
