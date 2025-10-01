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
