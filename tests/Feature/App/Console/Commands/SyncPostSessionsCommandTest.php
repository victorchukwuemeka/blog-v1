<?php

use App\Models\Post;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Http;
use App\Console\Commands\SyncPostSessionsCommand;

beforeEach(fn () => Http::allowStrayRequests());

it('fetches analytics data for each post and updates the sessions_count', function () {
    $foo = Post::factory()->create(['slug' => 'foo', 'sessions_count' => 0]);
    $bar = Post::factory()->create(['slug' => 'bar', 'sessions_count' => 0]);

    Http::fake([
        'api.pirsch.io/api/v1/statistics/page*' => Http::response([
            ['path' => '/foo', 'sessions' => 5],
            ['path' => '/foo#section', 'sessions' => 3],
            ['path' => '/bar', 'sessions' => 7],
        ]),
    ]);

    artisan(SyncPostSessionsCommand::class)
        ->assertSuccessful();

    expect($foo->refresh()->sessions_count)->toBe(8)
        ->and($bar->refresh()->sessions_count)->toBe(7);
});
