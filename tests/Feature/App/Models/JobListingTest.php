<?php

use App\Models\Company;
use App\Models\JobListing;
use Carbon\CarbonImmutable;

it('generates a slug on create from title and company name', function () {
    $company = Company::factory()->create(['name' => 'Acme Inc']);

    $listing = JobListing::factory()->for($company)->create([
        'title' => 'Senior PHP Developer',
    ]);

    expect($listing->slug)->toBe('senior-php-developer-acme-inc');
});

it('casts attributes correctly', function () {
    $listing = JobListing::factory()->create();

    expect($listing->technologies)->toBeArray()
        ->and($listing->locations)->toBeIterable()
        ->and($listing->how_to_apply)->toBeArray()
        ->and($listing->perks)->toBeArray()
        ->and($listing->interview_process)->toBeArray()
        ->and($listing->equity)->toBeBool()
        ->and($listing->published_on)->toBeInstanceOf(CarbonImmutable::class);
});

it('belongs to a company', function () {
    $listing = JobListing::factory()->create();

    expect($listing->company)->toBeInstanceOf(Company::class);
});
