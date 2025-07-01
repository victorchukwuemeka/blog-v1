<?php

use App\Models\User;
use Facades\Github\Client;
use App\Actions\RefreshUserData;

it('fetches GitHub user data and updates the user model', function () {
    $data = [
        'login' => 'foo',
        'name' => 'Foo',
        'bio' => 'Lorem ipsum dolor sit amet.',
    ];

    Client::shouldReceive('api->show')
        ->once()
        ->andReturn($data);

    app(RefreshUserData::class)->refresh($user = User::factory()->create([
        'github_login' => 'foo',
        'github_data' => [],
    ]));

    expect($user->refresh()->github_data['user'])->toMatchArray($data);
});
