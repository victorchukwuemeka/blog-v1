<?php

use App\Models\Company;
use App\Models\Job;

it('generates a slug on create from title and company name', function () {
    $company = Company::factory()->create(['name' => 'Acme Inc']);

    $listing = Job::factory()->for($company)->create([
        'title' => 'Senior PHP Developer',
    ]);

    expect($listing->slug)->toBe('senior-php-developer-acme-inc');
});

it('casts attributes correctly', function () {
    $listing = Job::factory()->create();

    expect($listing->technologies)->toBeArray()
        ->and($listing->locations)->toBeIterable()
        ->and($listing->how_to_apply)->toBeArray()
        ->and($listing->perks)->toBeArray()
        ->and($listing->interview_process)->toBeArray()
        ->and($listing->equity)->toBeBool();
});

it('belongs to a company', function () {
    $listing = Job::factory()->create();

    expect($listing->company)->toBeInstanceOf(Company::class);
});
