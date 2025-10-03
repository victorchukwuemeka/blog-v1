<?php

use App\Models\Company;
use App\Models\Job;

it('generates a slug on create from title and company name', function () {
    $company = Company::factory()->create(['name' => 'Acme Inc']);

    $job = Job::factory()->for($company)->create([
        'title' => 'Senior PHP Developer',
    ]);

    expect($job->slug)->toBe('senior-php-developer-acme-inc');
});

it('casts attributes correctly', function () {
    $job = Job::factory()->create();

    expect($job->technologies)->toBeArray()
        ->and($job->locations)->toBeIterable()
        ->and($job->how_to_apply)->toBeArray()
        ->and($job->perks)->toBeArray()
        ->and($job->interview_process)->toBeArray()
        ->and($job->equity)->toBeBool();
});

it('belongs to a company', function () {
    $job = Job::factory()->create();

    expect($job->company)->toBeInstanceOf(Company::class);
});
