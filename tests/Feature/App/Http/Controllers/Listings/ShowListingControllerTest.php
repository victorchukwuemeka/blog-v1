<?php

use App\Models\Listing;

use function Pest\Laravel\get;

it('shows a listing', function () {
    $listing = Listing::factory()->create();

    get(route('listings.show', $listing))
        ->assertOk()
        ->assertViewIs('listings.show')
        ->assertViewHas('listing', $listing);
});

it('returns 404 for unknown listing', function () {
    get(route('listings.show', 'non-existent'))
        ->assertNotFound();
});
