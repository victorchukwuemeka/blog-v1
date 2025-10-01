<?php

use App\Models\User;
use App\Models\Company;
use App\Models\JobListing;
use App\Notifications\JobListingFetched;
use Illuminate\Support\Facades\Notification;
use App\Actions\CreateJobListing as CreateJobListingAction;

it('creates or updates company and job listing and notifies admin', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $data = (object) defaultJobListingPayload();

    $listing = app(CreateJobListingAction::class)->create($data);

    expect($listing)->toBeInstanceOf(JobListing::class)
        ->and($listing->url)->toBe($data->url)
        ->and($listing->company->name)->toBe('Acme Inc')
        ->and($listing->technologies)->toMatchArray(['PHP', 'Laravel', 'MySQL'])
        ->and($listing->perks)->toMatchArray(['Remote stipend', 'Wellness budget'])
        ->and($listing->equity)->toBeTrue();

    Notification::assertSentToTimes($admin, JobListingFetched::class, 1);
});

it('updates existing company and listing when matching identifiers', function () {
    Notification::fake();

    $company = Company::factory()->create([
        'name' => 'Acme Inc',
    ]);

    $existing = JobListing::factory()->for($company)->create([
        'url' => 'https://example.com/jobs/dup',
        'title' => 'Old title',
        'min_salary' => 1,
    ]);

    $data = (object) array_merge(defaultJobListingPayload(), [
        'url' => 'https://example.com/jobs/dup', // triggers update for listing
        'source' => 'ExampleBoard',
        'title' => 'New title',
        'description' => 'New description',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => ['Remote'],
        'min_salary' => null, // Should default to 0.
        'max_salary' => null, // Should default to 0.
        'how_to_apply' => ['Apply on website'],
        'company' => (object) [
            'name' => 'Acme Inc', // Triggers update for company.
            'url' => 'https://acme.new',
            'logo' => 'https://cdn.test/acme-new.png',
            'about' => 'Updated about.',
        ],
    ]);

    $updated = app(CreateJobListingAction::class)->create($data)->refresh();

    expect($updated->id)->toBe($existing->id)
        ->and($updated->title)->toBe('New title')
        ->and($updated->min_salary)->toBe(0)
        ->and($updated->max_salary)->toBe(0)
        ->and($updated->company_id)->toBe($company->id)
        ->and($updated->company->url)->toBe('https://acme.new')
        ->and($updated->company->logo)->toBe('https://cdn.test/acme-new.png')
        ->and($updated->company->about)->toBe('Updated about.');

    Notification::assertNothingSent();
});

it('does not error if admin user is missing', function () {
    Notification::fake();

    $data = (object) array_merge(defaultJobListingPayload(), [
        'url' => 'https://example.com/jobs/456',
        'source' => 'Board',
        'title' => 'Role',
        'description' => 'Desc',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => [],
        'min_salary' => 0,
        'max_salary' => 0,
        'equity' => false,
        'interview_process' => [],
        'how_to_apply' => ['Apply'],
        'company' => (object) [
            'name' => 'Foo LLC',
            'url' => null,
            'logo' => null,
            'about' => 'About Foo.',
        ],
    ]);

    $listing = app(CreateJobListingAction::class)->create($data);

    expect($listing)->toBeInstanceOf(JobListing::class);
    Notification::assertNothingSent();
});

function defaultJobListingPayload() : array
{
    return [
        'url' => 'https://example.com/jobs/123',
        'source' => 'ExampleBoard',
        'language' => 'en',
        'title' => 'Senior PHP Developer',
        'description' => 'Build and maintain Laravel apps.',
        'technologies' => ['PHP', 'Laravel', 'MySQL'],
        'perks' => ['Remote stipend', 'Wellness budget'],
        'locations' => ['Remote', 'US'],
        'setting' => 'fully-remote',
        'min_salary' => 100000,
        'max_salary' => 150000,
        'currency' => 'USD',
        'equity' => true,
        'interview_process' => ['Recruiter screen', 'Technical interview'],
        'how_to_apply' => ['Submit resume', 'Complete coding challenge'],
        'company' => (object) [
            'name' => 'Acme Inc',
            'url' => 'https://acme.test',
            'logo' => 'https://cdn.test/acme.png',
            'about' => 'Acme builds tools for developers.',
        ],
    ];
}
