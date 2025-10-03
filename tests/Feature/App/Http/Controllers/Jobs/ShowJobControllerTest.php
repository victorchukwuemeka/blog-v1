<?php

use App\Models\Job;

use function Pest\Laravel\get;

it('shows a listing', function () {
    $listing = Job::factory()->create();

    get(route('jobs.show', $listing))
        ->assertOk()
        ->assertViewIs('jobs.show')
        ->assertViewHas('job', $listing);
});

it('returns 404 for unknown listing', function () {
    get(route('jobs.show', 'non-existent'))
        ->assertNotFound();
});

it('renders JobPosting JSON-LD on the job detail page', function () {
    $listing = Job::factory()->create();

    $response = get(route('jobs.show', $listing->slug));

    $response->assertOk();

    $response->assertSee('<script type="application/ld+json">', false);
    $response->assertSee('"@type": "JobPosting"', false);
    $response->assertSee('"title": ' . json_encode($listing->title), false);
});
