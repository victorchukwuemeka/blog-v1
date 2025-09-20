<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Actions\RevisePost;
use App\Notifications\NewRevision;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Notification;
use OpenAI\Responses\Responses\CreateResponse;

it('creates a revision for the report and notifies the admin', function () {
    Notification::fake();

    $post = Post::factory()->create();

    $report = Report::factory()->create([
        'post_id' => $post->id,
    ]);

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'serp_title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                        ],
                        'required' => ['title', 'serp_title', 'description', 'content'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'output' => [
                [
                    'type' => 'message',
                    'status' => 'completed',
                    'role' => 'assistant',
                    'content' => [[
                        'type' => 'output_text',
                        'text' => json_encode([
                            'title' => 'Revised Title',
                            'serp_title' => 'Revised SERP Title',
                            'description' => 'Revised Description',
                            'content' => 'Revised Content',
                        ]),
                        'annotations' => [],
                    ]],
                ],
                [
                    'type' => 'web_search_call',
                    'id' => 'ws_dummy',
                    'status' => 'completed',
                ],
            ],
        ]),
    ]);

    $revision = app(RevisePost::class)->revise($post, $report, 'Blah blah blah');

    expect($revision->report->is($report))->toBeTrue()
        ->and($revision->data)->toBeArray()
        ->and($revision->data['title'] ?? null)->toBe('Revised Title')
        ->and($revision->data['content'] ?? null)->toBe('Revised Content');

    Notification::assertSentToTimes($admin, NewRevision::class, 1);
});

it('does not error if admin user is missing', function () {
    Notification::fake();

    $post = Post::factory()->create();

    $report = Report::factory()->create([
        'post_id' => $post->id,
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'serp_title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                        ],
                        'required' => ['title', 'serp_title', 'description', 'content'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'output' => [
                [
                    'type' => 'message',
                    'status' => 'completed',
                    'role' => 'assistant',
                    'content' => [[
                        'type' => 'output_text',
                        'text' => json_encode([
                            'title' => 'Another Revised Title',
                            'serp_title' => 'Another Revised SERP Title',
                            'description' => 'Another Revised Description',
                            'content' => 'Another Revised Content',
                        ]),
                        'annotations' => [],
                    ]],
                ],
                [
                    'type' => 'web_search_call',
                    'id' => 'ws_dummy',
                    'status' => 'completed',
                ],
            ],
        ]),
    ]);

    $revision = app(RevisePost::class)->revise($post, $report, 'Blah blah blah');

    expect($revision->data['title'] ?? null)->toBe('Another Revised Title');

    Notification::assertNothingSent();
});
