<?php

use App\Models\Post;
use App\Models\Category;
use Carbon\CarbonImmutable;

it('belongs to many posts', function () {
    $category = Category::factory()
        ->has(Post::factory(3))
        ->create();

    expect($category->posts)->toHaveCount(3);
});

it('returns activity: top 5 published posts ordered by sessions_count desc', function () {
    // Create 6 published posts with varying sessions_count.
    $category = Category::factory()->create();

    $posts = Post::factory(6)->create([
        'published_at' => now()->subDay(),
    ]);

    $category->posts()->attach($posts->pluck('id'));

    // Give them specific sessions_count to assert ordering and limit.
    $posts->each(function (Post $post, int $i) {
        $post->update(['sessions_count' => $i * 10]); // 0, 10, ..., 50
    });

    $activity = $category->activity()->get();

    expect($activity)->toHaveCount(5);

    // Ensure sorted desc by sessions_count and only published posts.
    $counts = $activity->pluck('sessions_count')->all();
    expect($counts)->toBe([50, 40, 30, 20, 10]);

    $activity->each(fn (Post $p) => expect($p->published_at)->not->toBeNull());
});
