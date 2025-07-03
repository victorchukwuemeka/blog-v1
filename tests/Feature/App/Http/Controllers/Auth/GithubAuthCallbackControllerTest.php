<?php

use App\Models\User;
use App\Notifications\Welcome;
use Illuminate\Support\Facades\Date;

use function Pest\Laravel\assertGuest;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;

use function Pest\Laravel\assertDatabaseHas;

use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertAuthenticated;

it('creates a new user, sends a welcome notification, and redirects to intended URL', function () {
    Date::setTestNow(now());

    Notification::fake();

    $provider = Mockery::mock(GithubProvider::class);
    $provider->shouldReceive('user')->andReturn(new class
    {
        public function getAvatar()
        {
            return 'https://example.com/avatar.png';
        }

        public function getEmail()
        {
            return 'test@example.com';
        }

        public function getName()
        {
            return 'Test User';
        }

        public function getNickname()
        {
            return 'testuser';
        }
    });

    Socialite::shouldReceive('driver')
        ->with('github')
        ->andReturn($provider);

    // Set the intended URL this way to make the test pass. Using from() doesn't work.
    session()->put('url.intended', route('posts.index'));

    assertGuest()
        ->get(route('auth.callback'))
        ->assertRedirect(route('posts.index'))
        ->assertSessionHas('status', 'You have been logged in.');

    assertAuthenticated();

    assertDatabaseHas(User::class, [
        'email' => 'test@example.com',
        'name' => 'Test User',
        'github_login' => 'testuser',
        'refreshed_at' => now()->toDateTimeString(),
    ]);

    Notification::assertSentTo(
        User::query()->where('email', 'test@example.com')->first(),
        Welcome::class
    );
});

it('updates an existing user and redirects to intended URL', function () {
    Date::setTestNow(now());

    Notification::fake();

    $provider = Mockery::mock(GithubProvider::class);
    $provider->shouldReceive('user')->andReturn(new class
    {
        public function getAvatar()
        {
            return 'https://example.com/avatar.png';
        }

        public function getEmail()
        {
            return 'test@example.com';
        }

        public function getName()
        {
            return 'New Name';
        }

        public function getNickname()
        {
            return 'newusername';
        }
    });

    Socialite::shouldReceive('driver')
        ->with('github')
        ->andReturn($provider);

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    // Set the intended URL this way to
    session()->put('url.intended', route('posts.index'));

    assertGuest()
        ->get(route('auth.callback'))
        ->assertRedirect(route('posts.index'))
        ->assertSessionHas('status', 'You have been logged in.');

    assertAuthenticated();

    $user->refresh();

    expect($user->email)->toBe('test@example.com');
    expect($user->name)->toBe('New Name');
    expect($user->github_login)->toBe('newusername');
    expect($user->refreshed_at->getTimestamp())->toBe(now()->getTimestamp());

    Notification::assertNothingSentTo($user);
});
