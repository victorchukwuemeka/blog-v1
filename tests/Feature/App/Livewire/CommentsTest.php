<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Livewire\Comments;
use App\Notifications\NewComment;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;

use Illuminate\Support\Facades\Notification;

it('allows users to comment on a post and notifies the admin', function () {
    Notification::fake();

    $post = Post::factory()->create();

    $user = User::factory()->create();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($user);

    livewire(Comments::class, ['postId' => $post->id])
        ->call('store', null, 'Lorem ipsum dolor sit amet.');

    assertDatabaseHas(Comment::class, [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'content' => 'Lorem ipsum dolor sit amet.',
    ]);

    Notification::assertSentToTimes($admin, NewComment::class, 1);
});

it("doesn't notify the admin if the user is the admin", function () {
    Notification::fake();

    $post = Post::factory()->create();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    livewire(Comments::class, ['postId' => $post->id])
        ->call('store', null, 'Lorem ipsum dolor sit amet.');

    Notification::assertNotSentTo($admin, NewComment::class);
});

it("doesn't allow guests to comment", function () {
    livewire(Comments::class, ['postId' => 1])
        ->call('store', null, 'Lorem ipsum dolor sit amet.')
        ->assertStatus(401);
});

it('allows users to delete their own comments', function () {
    $post = Post::factory()->create();

    $comment = Comment::factory()
        ->for($post)
        ->create();

    actingAs($comment->user);

    livewire(Comments::class, ['postId' => $post->id])
        ->call('delete', $comment->id);

    expect($comment->exists())->toBeFalse();
});

it("doesn't allow users to delete comments they don't own", function () {
    $post = Post::factory()->create();

    $comment = Comment::factory()
        ->for($post)
        ->create();

    $otherUser = User::factory()->create();

    actingAs($otherUser);

    livewire(Comments::class, ['postId' => $post->id])
        ->call('delete', $comment->id)
        ->assertStatus(403);
});

it("doesn't allow guests to delete comments", function () {
    $post = Post::factory()->create();

    $comment = Comment::factory()
        ->for($post)
        ->create();

    livewire(Comments::class, ['postId' => $post->id])
        ->call('delete', $comment->id)
        ->assertStatus(401);
});
