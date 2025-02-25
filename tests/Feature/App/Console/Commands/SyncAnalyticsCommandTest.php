<?php

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Http;
use App\Console\Commands\SyncAnalyticsCommand;

it('fetches analytics data', function () {
    Http::fake([
        'api.pirsch.io/api/v1/token*' => Http::response(['access_token' => 'some-access-token']),
        'api.pirsch.io/api/v1/statistics/total*' => Http::response([
            'visitors' => 1234,
            'views' => 1234,
            'sessions' => 1234,
        ]),
        'api.pirsch.io/api/v1/statistics/platform*' => Http::response([
            'relative_platform_desktop' => 0.1234,
        ]),
    ]);

    artisan(SyncAnalyticsCommand::class)
        ->assertSuccessful();

    expect(cache()->has('platform_desktop'))->toBeTrue();
    expect(cache()->has('sessions'))->toBeTrue();
    expect(cache()->has('views'))->toBeTrue();
    expect(cache()->has('visitors'))->toBeTrue();
});
