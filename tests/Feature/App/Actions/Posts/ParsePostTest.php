<?php

use Carbon\CarbonImmutable;
use App\Actions\Posts\ParsePost;
use Symfony\Component\Yaml\Exception\ParseException;

it('parses a post', function () {
    $post = app(ParsePost::class)
        ->parse(__DIR__ . '/../../../../Fixtures/posts/test-post.md');

    expect($post)
        ->toHaveKeys(['image', 'title', 'slug', 'content', 'description', 'published_at', 'modified_at', 'canonical']);

    expect($post['image'])
        ->toContain('/images/test.jpg');

    expect($post['title'])
        ->toContain('Test Post Title');

    expect($post['slug'])
        ->toContain('test-post');

    expect($post['content'])
        ->toContain('This is the test post content.');

    expect($post['description'])
        ->toContain('This is a test post description');

    expect($post['published_at']->timestamp)
        ->toBe(1704067200);

    expect($post['modified_at']->timestamp)
        ->toBe(1704153600);

    expect($post['canonical'])
        ->toBe('https://example.com/test-post');
});

it('still parses the post even if the front matter is missing', function () {
    $post = app(ParsePost::class)
        ->parse(__DIR__ . '/../../../../Fixtures/posts/missing-front-matter.md');

    expect($post)
        ->toHaveKeys(['image', 'title', 'slug', 'content', 'description', 'published_at', 'modified_at', 'canonical']);

    expect($post['image'])
        ->toBeNull();

    expect($post['title'])
        ->toBeNull();

    expect($post['description'])
        ->toBeNull();

    expect($post['published_at'])
        ->toBeNull();

    expect($post['modified_at'])
        ->toBeNull();

    expect($post['canonical'])
        ->toBeNull();

    expect($post['slug'])
        ->toContain('missing-front-matter');

    expect($post['content'])
        ->toContain('This is a markdown file with no front matter.');
});

it('handles incomplete front matter', function () {
    $post = app(ParsePost::class)
        ->parse(__DIR__ . '/../../../../Fixtures/posts/incomplete-front-matter.md');

    expect($post)
        ->toHaveKeys(['image', 'title', 'slug', 'content', 'description', 'published_at', 'modified_at', 'canonical']);

    expect($post['image'])
        ->toContain('/images/incomplete.jpg');

    expect($post['title'])
        ->toBeNull();

    expect($post['description'])
        ->toContain('This is a test post with incomplete front matter');

    expect($post['published_at'])
        ->toBeNull();

    expect($post['modified_at'])
        ->not->toBeNull();

    expect($post['canonical'])
        ->toBeNull();

    expect($post['slug'])
        ->toContain('incomplete-front-matter');
});

it('throws an exception on malformed front matter', function () {
    expect(
        fn () => app(ParsePost::class)
            ->parse(__DIR__ . '/../../../../Fixtures/posts/malformed-front-matter.md')
    )
        ->toThrow(ParseException::class);
});

it('handles empty content', function () {
    $post = app(ParsePost::class)
        ->parse(__DIR__ . '/../../../../Fixtures/posts/empty-content.md');

    expect($post)
        ->toHaveKeys(['image', 'title', 'slug', 'content', 'description', 'published_at', 'modified_at', 'canonical']);

    expect($post['title'])
        ->toContain('Empty Content Post');

    expect($post['content'])
        ->toBe("\n");
});

it('gets the slug from the filename', function () {
    $post = app(ParsePost::class)
        ->parse(__DIR__ . '/../../../../Fixtures/posts/slug.md');

    expect($post['slug'])->toBe('slug');
});

it('handles various date formats', function () {
    $post = app(ParsePost::class)
        ->parse(__DIR__ . '/../../../../Fixtures/posts/various-date-formats.md');

    expect($post['published_at'])
        ->toBeInstanceOf(CarbonImmutable::class);

    expect($post['modified_at'])
        ->toBeInstanceOf(CarbonImmutable::class);
});
