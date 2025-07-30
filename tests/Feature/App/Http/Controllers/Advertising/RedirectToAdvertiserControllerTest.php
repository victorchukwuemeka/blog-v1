<?php

use App\Jobs\TrackEvent;

use function Pest\Laravel\get;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::allowStrayRequests());

it('redirects to the advertiser and appends additional query string parameters', function () {
    Bus::fake();

    get(route('redirect-to-advertiser', [
        'slug' => 'sevalla',
        'foo' => 'bar',
        'baz' => 'qux',
    ]))
        ->assertRedirect(config('advertisers.sevalla') . '?foo=bar&baz=qux&utm_source=benjamin_crozat');

    Bus::assertDispatchedAfterResponse(TrackEvent::class, function (TrackEvent $job) {
        expect($job->name)->toBe('Clicked on ad');

        expect($job->meta)->toBe([
            'slug' => 'sevalla',
            'url' => config('advertisers.sevalla'),
        ]);

        return true;
    });
});

it('throws a 404 if the advertiser is not found', function () {
    get(route('redirect-to-advertiser', 'foo'))
        ->assertNotFound();
});
