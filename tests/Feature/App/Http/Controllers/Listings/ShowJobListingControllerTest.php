<?php

use App\Models\JobListing;

use function Pest\Laravel\get;

it('shows a listing', function () {
    $listing = JobListing::factory()->create();

    get(route('job-listings.show', $listing))
        ->assertOk()
        ->assertViewIs('job-listings.show')
        ->assertViewHas('jobListing', $listing);
});

it('returns 404 for unknown listing', function () {
    get(route('job-listings.show', 'non-existent'))
        ->assertNotFound();
});

it('renders JobPosting JSON-LD on the job detail page', function () {
    $listing = JobListing::factory()->create();

    $response = get(route('job-listings.show', $listing->slug));

    $response->assertOk();

    $response->assertSee('<script type="application/ld+json">', false);
    $response->assertSee('"@type":"JobPosting"', false);
    $response->assertSee('"title":' . json_encode($listing->title), false);
});
