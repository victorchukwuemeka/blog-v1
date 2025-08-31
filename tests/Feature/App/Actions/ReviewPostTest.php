<?php

use App\Models\Post;
use App\Models\User;
use App\Actions\ReviewPost;
use App\Notifications\NewReport;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Notification;
use OpenAI\Responses\Responses\CreateResponse;

it('creates a report for the post and notifies the admin', function () {
    Notification::fake();

    $post = Post::factory()->create();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'output' => [
                [
                    'type' => 'message',
                    'role' => 'assistant',
                    'content' => [[
                        'type' => 'output_text',
                        'text' => 'Generated report content',
                        'annotations' => [],
                    ]],
                ],
                [
                    'type' => 'web_search_call',
                ],
            ],
        ]),
    ]);

    $report = app(ReviewPost::class)->review($post, 'Blah blah blah');

    expect($report->post->is($post))->toBeTrue()
        ->and($report->content)->toBe('Generated report content');

    Notification::assertSentToTimes($admin, NewReport::class, 1);
});

it('does not error if admin user is missing', function () {
    Notification::fake();

    $post = Post::factory()->create();

    OpenAI::fake([
        CreateResponse::fake([
            'output' => [
                [
                    'type' => 'message',
                    'role' => 'assistant',
                    'content' => [[
                        'type' => 'output_text',
                        'text' => 'Another report content',
                        'annotations' => [],
                    ]],
                ],
                [
                    'type' => 'web_search_call',
                ],
            ],
        ]),
    ]);

    $report = app(ReviewPost::class)->review($post, 'Blah blah blah');

    expect($report->content)->toBe('Another report content');

    Notification::assertNothingSent();
});
