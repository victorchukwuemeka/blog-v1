<?php

use App\Models\Job;

use function Pest\Laravel\get;

it('shows a job', function () {
    $job = Job::factory()->create();

    get(route('jobs.show', $job))
        ->assertOk()
        ->assertViewIs('jobs.show')
        ->assertViewHas('job', $job);
});

it('returns 404 for unknown job', function () {
    get(route('jobs.show', 'non-existent'))
        ->assertNotFound();
});

it('renders JobPosting JSON-LD on the job detail page', function () {
    $job = Job::factory()->create();

    $response = get(route('jobs.show', $job->slug));

    $response->assertOk();

    $response->assertSee('<script type="application/ld+json">', false);
    $response->assertSee('"@type": "JobPosting"', false);
    $response->assertSee('"title": ' . json_encode($job->title), false);
});
