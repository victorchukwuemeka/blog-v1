<?php

use App\Models\User;
use Facades\Github\Client;
use App\Actions\RefreshUserData;
use Illuminate\Support\Facades\Date;

it('fetches GitHub user data and updates the user model', function () {
    Date::setTestNow(now());

    $data = [
        'login' => 'foo',
        'name' => 'Foo',
        'bio' => 'Lorem ipsum dolor sit amet.',
    ];

    Client::shouldReceive('api->showById')
        ->once()
        ->andReturn($data);

    app(RefreshUserData::class)->refresh($user = User::factory()->create([
        'github_login' => 'foo',
        'github_data' => ['id' => 123],
    ]));

    expect($user->refresh()->github_data['user'])->toMatchArray($data);
    expect($user->refresh()->refreshed_at->getTimestamp())->toBe(now()->getTimestamp());
});
