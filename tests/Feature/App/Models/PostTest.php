<?php

use App\Models\Post;
use App\Models\Redirect;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;

it('generates a slug when a post is created if none is provided', function () {
    $post = Post::factory()->create([
        'title' => 'Foo Bar Baz',
    ]);

    expect($post->slug)->toBe('foo-bar-baz');
});

it("does not generate a slug when it's already provided", function () {
    $post = Post::factory()->create([
        'title' => 'Foo Bar Baz',
        'slug' => 'lorem-ipsum-dolor-sit-amet',
    ]);

    expect($post->slug)->toBe('lorem-ipsum-dolor-sit-amet');
});

it('does not update the slug when title is changed', function () {
    $post = Post::factory()->create();

    $post->update(['title' => 'Foo Bar Baz']);

    expect($post->slug)->not->toBe('foo-bar-baz');
});

it('creates a redirect when the slug is changed', function () {
    $post = Post::factory()->create();

    $old = $post->slug;

    assertDatabaseCount(Redirect::class, 0);

    $post->update(['slug' => 'foo-bar-baz']);

    assertDatabaseHas(Redirect::class, [
        'from' => $old,
        'to' => 'foo-bar-baz',
    ]);
});

it('keeps only one redirect per original slug, even when toggling back and forth', function () {
    $post = Post::factory()->create([
        'slug' => 'foo',
    ]);

    // First change: foo -> bar (creates foo -> bar).
    $post->update(['slug' => 'bar']);

    assertDatabaseCount(Redirect::class, 1);

    // Change back: bar -> foo (should remove foo -> bar and create bar -> foo).
    $post->update(['slug' => 'foo']);

    assertDatabaseCount(Redirect::class, 1);

    assertDatabaseMissing(Redirect::class, [
        'from' => 'foo',
        'to' => 'bar',
    ]);

    assertDatabaseHas(Redirect::class, [
        'from' => 'bar',
        'to' => 'foo',
    ]);

    // Change again: foo -> bar (should delete bar -> foo and recreate foo -> bar).
    $post->update(['slug' => 'bar']);

    assertDatabaseCount(Redirect::class, 1);

    assertDatabaseMissing(Redirect::class, [
        'from' => 'bar',
        'to' => 'foo',
    ]);

    assertDatabaseHas(Redirect::class, [
        'from' => 'foo',
        'to' => 'bar',
    ]);
});

it('avoids circular redirects', function () {
    $post = Post::factory()->create([
        'slug' => 'foo',
    ]);

    // Pre-existing redirect bar -> foo.
    Redirect::query()->create([
        'from' => 'bar',
        'to' => 'foo',
    ]);

    // Changing foo -> bar would have produced foo -> bar, forming a loop.
    $post->update(['slug' => 'bar']);

    // The listener should have deleted the conflicting bar -> foo row and
    // created foo -> bar with no additional duplicates.
    assertDatabaseCount(Redirect::class, 1);

    assertDatabaseHas(Redirect::class, [
        'from' => 'foo',
        'to' => 'bar',
    ]);
});

it('updates existing redirects to avoid multi-hop chains', function () {
    $post = Post::factory()->create([
        'slug' => 'a',
    ]);

    // a -> b
    $post->update(['slug' => 'b']);

    // b -> c
    $post->update(['slug' => 'c']);

    // We should now have exactly two redirects, both pointing directly to "c".
    assertDatabaseCount(Redirect::class, 2);

    assertDatabaseHas(Redirect::class, [
        'from' => 'a',
        'to' => 'c',
    ]);

    assertDatabaseHas(Redirect::class, [
        'from' => 'b',
        'to' => 'c',
    ]);

    assertDatabaseMissing(Redirect::class, [
        'from' => 'a',
        'to' => 'b',
    ]);
});

it('generates a prompt for LLMs', function () {
    $post = Post::factory()->create([
        'title' => 'Foo Bar Baz',
        'content' => 'Foo **bar** baz [qux](https://example.com)',
    ]);

    expect($post->toPrompt())->toContain('Foo Bar Baz Foo bar baz');
    expect($post->toPrompt())->toContain('href="https://example.com">qux</a>');
});
