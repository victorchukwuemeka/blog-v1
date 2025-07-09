<?php

use App\Models\Link;
use App\Jobs\RecommendPosts;
use App\Actions\CreatePostForLink;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use OpenAI\Responses\Chat\CreateResponse;
use Facades\fivefilters\Readability\Readability;

it('creates a post for a link and generates recommendations for it', function () {
    Bus::fake();

    Http::fake();

    OpenAI::fake([
        CreateResponse::fake([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'title' => 'Generated post title',
                            'content' => 'Generated post content.',
                            'description' => 'Generated meta description.',
                        ]),
                    ],
                ],
            ],
        ]),
    ]);

    Readability::shouldReceive('parse')->once();
    Readability::shouldReceive('getAuthor')->andReturn('Test Author');
    Readability::shouldReceive('getTitle')->andReturn('Test Title');
    Readability::shouldReceive('getContent')->andReturn('Test Content');

    Http::fake([
        '*' => Http::response('<html></html>', 200),
    ]);

    $link = Link::factory()->create();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post->title)->toBe('Generated post title');
    expect($post->content)->toBe('Generated post content.');
    expect($post->description)->toBe('Generated meta description.');

    Bus::assertDispatchedTimes(RecommendPosts::class, 1);
});
