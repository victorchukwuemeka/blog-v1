<?php

use App\Jobs\TrackEvent;

use function Pest\Laravel\get;

use Illuminate\Support\Facades\Bus;

it('redirects to the merchant with the same query parameters', function () {
    Bus::fake();

    get(route('merchants.show', ['ploi', 'foo' => 'bar']))
        ->assertRedirectContains(config('merchants.services.ploi') . '&foo=bar');

    Bus::assertDispatchedAfterResponse(TrackEvent::class, function (TrackEvent $job) {
        expect($job->name)->toBe('Clicked on merchant');

        expect($job->meta)->toBe([
            'slug' => 'ploi',
            'url' => config('merchants.services.ploi'),
        ]);

        return true;
    });
});

test('it throws 404 when merchant does not exist', function () {
    get(route('merchants.show', 'foo'))
        ->assertNotFound();
});
