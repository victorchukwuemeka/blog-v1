<?php

use App\Models\Post;
use App\Models\Category;
use Carbon\CarbonImmutable;

it('casts modified_at to datetime', function () {
    $category = Category::factory()->create([
        'modified_at' => now(),
    ]);

    expect($category->modified_at)->toBeInstanceOf(CarbonImmutable::class);
});

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

it('calculates read_time from content word count (200 wpm, rounded up)', function () {
    $content = collect(range(1, 401))->map(fn () => 'word')->implode(' ');

    $category = Category::factory()->create([
        'content' => $content,
    ]);

    expect($category->read_time)->toBe(3.0);
});

it('renders a table of contents including a synthetic heading for related posts', function () {
    $category = Category::factory()->create([
        'name' => 'Laravel',
        'content' => <<< 'MD'
# Some heading

## Some other heading

Some text.
MD,
    ]);

    $html = (string) $category->toTableOfContents();

    expect($html)->toContain('Some heading');
    expect($html)->toContain('Some other heading');
    expect($html)->toContain('All articles about Laravel');
});
