<?php

use App\Actions\TrackEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

beforeEach(fn () => Http::allowStrayRequests());

it("successfully makes a call to Pirsch's API with valid parameters", function () {
    app(TrackEvent::class)->track(...parameters());
})->throwsNoExceptions();

it('handles an invalid token appropriately', function () {
    config(['services.pirsch.access_key' => 'invalid_token']);

    app(TrackEvent::class)->track(...parameters());
})->throws(RequestException::class);

it('retries on network failure and does not throw if it succeeds', function () {
    Http::fakeSequence('api.pirsch.io/api/v1/hit')
        ->pushStatus(503)
        ->pushStatus(503)
        ->pushStatus(200);

    app(TrackEvent::class)->track(...parameters());
})->throwsNoExceptions();

it('properly handles request timeouts', function () {
    Http::fakeSequence()
        ->pushStatus(408)
        ->pushStatus(408)
        ->pushStatus(200);

    app(TrackEvent::class)->track(...parameters());
})->throwsNoExceptions();

function parameters() : array
{
    return [
        'name' => 'Foo',
        'url' => fake()->url(),
        'ip' => fake()->ipv4(),
        'userAgent' => fake()->userAgent(),
        'acceptLanguage' => fake()->languageCode(),
        'referrer' => fake()->url(),
    ];
}
