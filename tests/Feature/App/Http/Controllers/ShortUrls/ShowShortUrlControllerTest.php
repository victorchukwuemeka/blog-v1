<?php

use App\Jobs\TrackEvent;
use App\Models\ShortUrl;

use function Pest\Laravel\get;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::allowStrayRequests());

it('redirects to the short URL and tracks the event', function () {
    Bus::fake();

    $shortUrl = ShortUrl::factory()->create();

    get(route('shortUrl.show', $shortUrl))
        ->assertStatus(302)
        ->assertRedirect($shortUrl->url);

    Bus::assertDispatchedAfterResponse(TrackEvent::class, function (TrackEvent $job) use ($shortUrl) {
        expect($job->name)->toBe('Clicked on short URL');

        expect($job->meta)->toBe([
            'url' => $shortUrl->url,
        ]);

        return true;
    });
});

it('throws a 404 if the short URL does not exist', function () {
    get(route('shortUrl.show', 'non-existing'))
        ->assertNotFound();
});
