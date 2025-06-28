<?php

use App\Models\User;

it('generates a slug from the name', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
    ]);

    expect($user->slug)->toBe('john-doe');
});
