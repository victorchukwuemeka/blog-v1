<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Carbon\CarbonImmutable;

it('casts modified_at to datetime', function () {
    $comment = Comment::factory()->create([
        'modified_at' => now(),
    ]);

    expect($comment->modified_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a user', function () {
    $comment = Comment::factory()->create();

    expect($comment->user)->toBeInstanceOf(User::class);
});

it('belongs to a post', function () {
    $comment = Comment::factory()->create();

    expect($comment->post)->toBeInstanceOf(Post::class);
});

it('belongs to a parent comment', function () {
    $comment = Comment::factory()
        ->for(Comment::factory(), 'parent')
        ->create();

    expect($comment->parent)->toBeInstanceOf(Comment::class);
});

it('has many children comments', function () {
    $comment = Comment::factory()
        ->has(Comment::factory(3), 'children')
        ->create();

    expect($comment->children)->toHaveCount(3);
});

it('has a truncated attribute that truncates if the content is longer than 100 characters', function () {
    $comment = Comment::factory()->create([
        'content' => fake()->text(200),
    ]);

    expect($comment->truncated)->toEndWith('…');
});

it("has a truncated attribute that doesn't truncate if the content is less than 100 characters", function () {
    $comment = Comment::factory()->create([
        'content' => 'This is a test comment',
    ]);

    expect($comment->truncated)->toBe('This is a test comment');
});

it('can delete itself and all its children', function () {
    $comment = Comment::factory()
        ->has(Comment::factory(3), 'children')
        ->create();

    $comment->deleteWithChildren();

    expect($comment->trashed())->toBeTrue();
    $comment->children->each(fn (Comment $child) => expect($child->trashed())->toBeTrue());
});
