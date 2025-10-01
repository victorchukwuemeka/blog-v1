<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Queue;
use App\Jobs\FetchJobListingData as FetchJobListingDataJob;
use App\Filament\Pages\FetchJobListingData as FetchJobListingDataPage;

it('dispatches the job and shows a notification', function () {
    Queue::fake();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    $url = 'https://example.com/job/123';

    livewire(FetchJobListingDataPage::class)
        ->fillForm([
            'url' => $url,
        ])
        ->call('submit');

    Queue::assertPushed(FetchJobListingDataJob::class, function ($job) use ($url) {
        return $job->url === $url;
    });
});
