<?php

use App\Jobs\CreateJob;
use Facades\App\Actions\CreateJob as CreateJobAction;

it('invokes the action with provided data', function () {
    $data = (object) [
        'url' => 'https://example.com/jobs/789',
        'source' => 'Board',
        'language' => 'en',
        'title' => 'Title',
        'description' => 'Desc',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => [],
        'setting' => 'fully-remote',
        'min_salary' => 0,
        'max_salary' => 0,
        'currency' => 'USD',
        'equity' => false,
        'interview_process' => [],
        'how_to_apply' => ['Apply'],
        'company' => (object) [
            'name' => 'Foo LLC',
            'url' => null,
            'logo' => null,
            'about' => 'About Foo.',
        ],
    ];

    CreateJobAction::shouldReceive('create')
        ->once()
        ->with($data);

    (new CreateJob($data))->handle();
});
