<?php

use App\Models\Job;
use App\Jobs\ScrapeJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use App\Console\Commands\IngestJobFeedsCommand;

it('dispatches ScrapeJob only for new URLs and respects limit', function () {
    // Seed an existing job to test dedupe by url.
    Job::factory()->create(['url' => 'https://larajobs.com/job/3720']);

    // Prepare a feed with two items (one existing, one new).
    $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Test</title>
    <item>
      <title>Existing</title>
      <link>https://larajobs.com/job/3720</link>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
    <item>
      <title>New One</title>
      <link>https://larajobs.com/job/9999</link>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
  </channel>
</rss>
XML;

    // Fake HTTP.
    Http::fake([
        'https://example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/rss+xml']),
    ]);

    // Configure feeds with a limit of 1 to test limit.
    config()->set('job_feeds', [[
        'name' => 'TestFeed',
        'url' => 'https://example.com/feed',
        'enabled' => true,
        'max_items_per_run' => 1,
    ]]);

    Bus::fake();

    $this->artisan(IngestJobFeedsCommand::class)->assertExitCode(0);

    // Should dispatch only once (the new url) due to dedupe+limit.
    Bus::assertDispatchedTimes(ScrapeJob::class, 1);
    Bus::assertDispatched(ScrapeJob::class, function (ScrapeJob $job) {
        return 'https://larajobs.com/job/9999' === $job->url;
    });
});

it('skips disabled feeds and handles 500 responses gracefully', function () {
    // One disabled feed and one failing feed.
    config()->set('job_feeds', [
        [
            'name' => 'Disabled',
            'url' => 'https://disabled/feed',
            'enabled' => false,
        ],
        [
            'name' => 'Failing',
            'url' => 'https://failing/feed',
            'enabled' => true,
        ],
    ]);

    // Fake HTTP: return 500 for failing feed.
    Http::fake([
        'https://failing/feed' => Http::response('error', 500, ['Content-Type' => 'text/plain']),
        'https://disabled/feed' => Http::response('', 200),
    ]);

    Bus::fake();

    $this->artisan(IngestJobFeedsCommand::class)->assertExitCode(0);

    // No jobs dispatched.
    Bus::assertNotDispatched(ScrapeJob::class);
});

it('filters by feed argument and supports dry-run and limit override', function () {
    // Two feeds in config, we will filter to the second by name.
    config()->set('job_feeds', [
        [
            'name' => 'FirstFeed',
            'url' => 'https://first.example.com/feed',
            'enabled' => true,
            'max_items_per_run' => 10,
        ],
        [
            'name' => 'SecondFeed',
            'url' => 'https://second.example.com/feed',
            'enabled' => true,
            'max_items_per_run' => 10,
        ],
    ]);

    // Mock HTTP: one response for the dry run.
    $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Second</title>
    <item>
      <title>Only</title>
      <link>https://second.example.com/only</link>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
  </channel>
</rss>
XML;

    Http::fake([
        'https://second.example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/rss+xml']),
    ]);

    Bus::fake();

    // Dry-run with limit override and filter; should not dispatch any jobs.
    $this->artisan('app:ingest-job-feeds', ['feed' => 'SecondFeed', '--limit' => 1, '--dry-run' => true])
        ->assertExitCode(0);

    Bus::assertNotDispatched(ScrapeJob::class);

    // Re-fake for the real run.
    Http::fake([
        'https://second.example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/rss+xml']),
    ]);

    // Real run with limit 1 should dispatch once.
    $this->artisan('app:ingest-job-feeds', ['feed' => 'SecondFeed', '--limit' => 1])
        ->assertExitCode(0);

    Bus::assertDispatchedTimes(ScrapeJob::class, 1);
});

it('dispatches multiple jobs when within limit', function () {
    // Provide two new items.
    $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Test</title>
    <item>
      <title>First</title>
      <link>https://example.com/1</link>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
    <item>
      <title>Second</title>
      <link>https://example.com/2</link>
      <pubDate>Wed, 01 Oct 2025 01:49:18 +0000</pubDate>
    </item>
  </channel>
</rss>
XML;

    Http::fake([
        'https://example.com/feed' => Http::response($xml, 200, ['Content-Type' => 'application/rss+xml']),
    ]);

    config()->set('job_feeds', [[
        'name' => 'Test',
        'url' => 'https://example.com/feed',
        'enabled' => true,
        'max_items_per_run' => 2,
    ]]);

    Bus::fake();

    $this->artisan(IngestJobFeedsCommand::class)->assertExitCode(0);

    // Assert two jobs dispatched.
    Bus::assertDispatchedTimes(ScrapeJob::class, 2);
});
