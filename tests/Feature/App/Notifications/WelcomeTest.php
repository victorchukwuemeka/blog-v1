<?php

use App\Models\User;
use App\Notifications\Welcome;
use Illuminate\Support\HtmlString;

it('renders as an email', function () {
    $result = new Welcome()->toMail(User::factory()->create())->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});
