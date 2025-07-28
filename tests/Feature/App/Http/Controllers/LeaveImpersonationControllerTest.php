<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('leaves impersonation and redirects to users list', function () {
    /** @var \App\Models\User $admin */
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $user = User::factory()->create();

    actingAs($admin);

    $admin->impersonate($user);

    session(['impersonate.return' => '/foo']);

    expect(session()->has(config('laravel-impersonate.session_key')))->toBeTrue();

    get(route('leave-impersonation'))
        ->assertRedirect('/foo');

    expect(session()->has(config('laravel-impersonate.session_key')))->toBeFalse();
});

it('redirects even when not impersonating', function () {
    /** @var \App\Models\User $admin */
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    session(['impersonate.return' => '/foo']);

    get(route('leave-impersonation'))
        ->assertRedirect('/foo');
});
