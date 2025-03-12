<?php

use App\Models\User;

use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertAuthenticated;

it('logs out an authenticated user', function () {
    $user = User::factory()->create();

    actingAs($user);

    assertAuthenticated();

    post(route('auth.logout'))
        ->assertRedirect(route('home'))
        ->assertSessionHas('status', 'You have been successfully logged out.');

    assertGuest();
});
