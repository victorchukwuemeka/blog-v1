<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('leaves impersonation and redirects to users list', function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $user = User::factory()->create();

    actingAs($admin);

    $admin->impersonate($user);

    expect(session()->has(config('laravel-impersonate.session_key')))->toBeTrue();

    get(route('leave-impersonation'))
        ->assertRedirect(route('filament.admin.resources.users.index'));

    expect(session()->has(config('laravel-impersonate.session_key')))->toBeFalse();
});

it('redirects even when not impersonating', function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    get(route('leave-impersonation'))
        ->assertRedirect(route('filament.admin.resources.users.index'));
});
