<?php

use function Pest\Laravel\get;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;

it('redirects to GitHub with correct scopes', function () {
    // Mock Socialite's GitHub driver.
    $provider = Mockery::mock(GithubProvider::class);

    // Make sure the user:email scoped is used.
    $provider->shouldReceive('scopes')
        ->once()
        ->with(['user:email'])
        ->andReturnSelf();

    $provider->shouldReceive('redirect')
        ->once()
        ->andReturn(redirect('https://github.com/login/oauth/authorize'));

    Socialite::shouldReceive('driver')
        ->once()
        ->with('github')
        ->andReturn($provider);

    get(route('auth.redirect'))
        ->assertRedirectContains('https://github.com/login/oauth/authorize');
});
