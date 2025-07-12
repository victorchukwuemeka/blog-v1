<?php

use App\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\actingAs;

it('shows the Cloudflare Images form to admins', function () {
    $user = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($user)
        ->get(route('show-cloudflare-images-form'))
        ->assertOk();
});

it('does not show the Cloudflare Images form to users', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson(route('show-cloudflare-images-form'))
        ->assertForbidden();
});

it('does not show the Cloudflare Images form to guests', function () {
    getJson(route('show-cloudflare-images-form'))
        ->assertUnauthorized();
});
