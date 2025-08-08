<?php

use App\Models\User;
use App\Models\Comment;
use App\Notifications\NewReply;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;

it('renders as an email', function () {
    $parent = Comment::factory()->create();

    $reply = Comment::factory()->for($parent->post)->create([
        'parent_id' => $parent->id,
    ]);

    $result = (new NewReply($reply))
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, greeting and action', function () {
    $parent = Comment::factory()->create();

    $reply = Comment::factory()->for($parent->post)->create([
        'parent_id' => $parent->id,
    ]);

    $message = (new NewReply($reply))->toMail(User::factory()->create());

    expect($message->subject)->toBe('Someone replied to your comment');

    expect($message->greeting)
        ->toContain($reply->user->name)
        ->toContain($reply->post->title)
        ->toContain(route('posts.show', $reply->post));

    expect($message->actionText)->toBe('Check Reply');

    expect($message->actionUrl)->toBe(route('posts.show', $reply->post) . '#comments');
});

it('sends via the mail channel and is queueable', function () {
    $parent = Comment::factory()->create();

    $reply = Comment::factory()->for($parent->post)->create([
        'parent_id' => $parent->id,
    ]);

    $user = User::factory()->create();

    $notification = new NewReply($reply);

    expect($notification->via($user))->toBe(['mail']);

    expect($notification)->toBeInstanceOf(ShouldQueue::class);
});
