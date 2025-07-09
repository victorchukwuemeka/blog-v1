<?php

use App\Models\Link;
use App\Models\Post;
use App\Actions\CreatePostForLink;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Facades\fivefilters\Readability\Readability;

it('creates a post for a link', function () {
    Http::fake();

    OpenAI::fake();

    Readability::shouldReceive('parse')->once();
    Readability::shouldReceive('getAuthor')->andReturn('Test Author');
    Readability::shouldReceive('getTitle')->andReturn('Test Title');
    Readability::shouldReceive('getContent')->andReturn('Test Content');

    Http::fake([
        '*' => Http::response('<html></html>', 200),
    ]);

    $link = Link::factory()->create();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post)->toBeInstanceOf(Post::class);
});
