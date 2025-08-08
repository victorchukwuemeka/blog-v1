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

it('has the expected subject and content', function () {
    $link = Link::factory()->create(['url' => 'https://example.com/some-page']);

    $message = (new LinkApproved($link))->toMail(User::factory()->create());

    expect($message->subject)->toBe('Your link was approved');
    expect($message->greeting)->toBe('Thank you for submitting!');
    expect($message->introLines)->not()->toBeEmpty();
    expect(implode("\n", $message->introLines))
        ->toContain('example.com')
        ->toContain('X')
        ->toContain('LinkedIn');
});

it('sends via the mail channel and is queueable', function () {
    $link = Link::factory()->create();
    $user = User::factory()->create();

    $notification = new LinkApproved($link);

    expect($notification->via($user))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
