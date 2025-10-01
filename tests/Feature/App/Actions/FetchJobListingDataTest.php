<?php

use App\Jobs\CreateJobListing;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use OpenAI\Responses\Responses\CreateResponse;
use App\Actions\FetchJobListingData as FetchJobListingDataAction;

it('dispatches CreateJobListing with parsed data from OpenAI', function () {
    Queue::fake();

    $url = 'https://example.com/job/123';

    Http::fake([
        $url => Http::response('', 200),
    ]);

    $payload = json_encode([
        'url' => $url,
        'language' => 'en',
        'title' => 'Senior PHP Developer',
        'description' => 'Build and maintain Laravel apps.',
        'technologies' => ['PHP', 'Laravel', 'MySQL'],
        'how_to_apply' => ['Submit resume', 'Complete coding challenge'],
        'locations' => ['Remote', 'US'],
        'setting' => 'fully-remote',
        'equity' => true,
        'min_salary' => 100000,
        'max_salary' => 150000,
        'currency' => 'USD',
        'perks' => ['Remote stipend'],
        'interview_process' => ['Recruiter screen', 'Technical interview'],
        'company' => [
            'name' => 'Acme Inc',
            'url' => 'https://acme.test',
            'logo' => 'https://cdn.test/acme.png',
            'about' => 'Acme builds tools for developers.',
        ],
        'source' => 'ExampleBoard',
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'job_listing',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => ['type' => 'string'],
                        ],
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
                        'text' => $payload,
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

    app(FetchJobListingDataAction::class)->fetch($url);

    Queue::assertPushed(CreateJobListing::class, function ($job) use ($url) {
        return isset($job->data) && is_object($job->data) && $job->data->url === $url;
    });
});

it('throws when the URL cannot be reached', function () {
    Queue::fake();

    $url = 'https://example.com/job/404';

    Http::fake([
        $url => Http::response('', 404),
    ]);

    expect(fn () => app(FetchJobListingDataAction::class)->fetch($url))
        ->toThrow(Exception::class);

    Queue::assertNothingPushed();
});
