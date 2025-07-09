<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\HtmlString;
use App\Notifications\LinkApproved;

it('renders as an email', function () {
    $link = Link::factory()->create();

    $result = new LinkApproved($link)
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});
