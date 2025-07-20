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

    get(route('redirect-short-url', $shortUrl))
        ->assertStatus(302)
        ->assertRedirect($shortUrl->url);

    Bus::assertDispatchedAfterResponse(TrackEvent::class);
});

it('throws a 404 if the short URL does not exist', function () {
    get(route('redirect-short-url', 'non-existing'))
        ->assertNotFound();
});
