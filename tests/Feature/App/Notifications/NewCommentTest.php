<?php

use App\Models\User;
use App\Models\Comment;
use App\Notifications\NewComment;
use Illuminate\Support\HtmlString;

it('renders as an email', function () {
    $comment = Comment::factory()->create();

    $result = new NewComment($comment)
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});
