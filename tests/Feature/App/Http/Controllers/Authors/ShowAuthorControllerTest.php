<?php

use App\Models\User;

use function Pest\Laravel\get;

it('shows a given author', function () {
    $user = User::factory()
        ->hasPosts(3)
        ->create();

    get(route('authors.show', $user))
        ->assertOk();
});
