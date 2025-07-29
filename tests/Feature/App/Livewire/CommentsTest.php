<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Livewire\Comments;
use App\Notifications\NewReply;
use App\Notifications\NewComment;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;

use Illuminate\Support\Facades\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

it('allows users to comment on a post and notifies the admin', function () {
    Notification::fake();

    $post = Post::factory()
        ->hasComments(15)
        ->create();

    $user = User::factory()->create();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($user);

    livewire(Comments::class, ['postId' => $post->id])
        ->assertViewHas('comments', function (LengthAwarePaginator $comments) {
            expect($comments->perPage())->toBe(10);

            return true;
        })
        ->assertViewHas('commentsCount', 15)
        ->call('store', null, 'Lorem ipsum dolor sit amet.');

    assertDatabaseHas(Comment::class, [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'content' => 'Lorem ipsum dolor sit amet.',
    ]);

    Notification::assertSentTimes(NewReply::class, 0);

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

    Notification::assertSentTimes(NewReply::class, 0);

    Notification::assertNotSentTo($admin, NewComment::class);
});

it("notifies the parent comment's author when a reply is posted", function () {
    Notification::fake();

    $user = User::factory()->create();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $existingComment = Comment::factory()->create();

    actingAs($user);

    livewire(Comments::class, ['postId' => $existingComment->post_id])
        ->call('store', $existingComment->id, 'Lorem ipsum dolor sit amet.');

    Notification::assertSentToTimes($existingComment->user, NewReply::class, 1);

    Notification::assertSentToTimes($admin, NewComment::class, 1);
});

it("doesn't allow guests to comment", function () {
    livewire(Comments::class, ['postId' => 1])
        ->call('store', null, 'Lorem ipsum dolor sit amet.')
        ->assertStatus(401);
});

it('allows users to delete their own comments and all their children', function () {
    $post = Post::factory()->create();

    $comment = Comment::factory()
        ->for($post)
        ->has(Comment::factory(3), 'children')
        ->create();

    actingAs($comment->user);

    livewire(Comments::class, ['postId' => $post->id])
        ->call('delete', $comment->id);

    expect(Comment::query()->count())->toBe(0);
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
