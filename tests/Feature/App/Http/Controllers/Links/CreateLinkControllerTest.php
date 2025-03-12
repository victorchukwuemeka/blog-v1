<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('shows the form to authenticated users', function () {
    actingAs(User::factory()->create())
        ->get(route('links.create'))
        ->assertOk()
        ->assertViewIs('links.create');
});

it('redirects guests', function () {
    get(route('links.create'))
        ->assertRedirect(route('login'));
});
