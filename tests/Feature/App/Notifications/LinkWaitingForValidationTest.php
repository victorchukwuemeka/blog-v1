<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\HtmlString;
use App\Notifications\LinkWaitingForValidation;

it('renders as an email', function () {
    $link = Link::factory()->create();

    $result = new LinkWaitingForValidation($link)
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});
