<?php

use App\Models\Metric;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Http;

use function Pest\Laravel\assertDatabaseHas;

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

    assertDatabaseHas(Metric::class, ['key' => 'platform_desktop']);
    assertDatabaseHas(Metric::class, ['key' => 'sessions']);
    assertDatabaseHas(Metric::class, ['key' => 'views']);
    assertDatabaseHas(Metric::class, ['key' => 'visitors']);
});
