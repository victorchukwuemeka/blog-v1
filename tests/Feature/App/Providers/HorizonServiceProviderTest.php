<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

it('allows admins to access Horizon', function () {
    $user = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($user)
        ->getJson(route('horizon.index'))
        ->assertOk();
});

it('disallows users to access Horizon', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson(route('horizon.index'))
        ->assertForbidden();
});

it('disallows guests to access Horizon', function () {
    assertGuest()
        ->getJson(route('horizon.index'))
        ->assertForbidden();
});
