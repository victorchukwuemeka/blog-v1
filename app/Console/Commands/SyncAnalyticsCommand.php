<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Attribute\AsCommand;

// This command uses Pirsch Analytics' API (https://benjamincrozat.com/recommends/pirsch-analytics) to fetch fresh numbers about my visitors.
#[AsCommand(name: 'app:sync-analytics')]
class SyncAnalyticsCommand extends Command
{
    protected $description = 'Fetch fresh numbers from the analytics provider';

    public function handle() : void
    {
        $this->info('Fetching fresh analytics data…');

        $data = $this->fetch();

        // The percentage of users on desktop. A metric advertisers love.
        cache()->put('platform_desktop', ($data['relative_platform_desktop'] ?? 0) * 100);

        // The number of sessions in the last 30 days.
        cache()->put('sessions', $data['sessions'] ?? 0);

        // The number of views in the last 30 days.
        cache()->put('views', $data['views'] ?? 0);

        // The number of visitors in the last 30 days.
        cache()->put('visitors', $data['visitors'] ?? 0);

        $this->info('Fresh analytics data has been fetched.');
    }

    protected function fetch() : array
    {
        $accessToken = Http::post('https://api.pirsch.io/api/v1/token', [
            'client_id' => config('services.pirsch.client_id'),
            'client_secret' => config('services.pirsch.client_secret'),
        ])
            ->throw()
            ->json('access_token');

        $overview = Http::withToken($accessToken)
            ->get('https://api.pirsch.io/api/v1/statistics/total', [
                'id' => config('services.pirsch.domain_id'),
                'from' => now()->subDays(31)->toDateString(),
                'to' => now()->subDay()->toDateString(),
                'timezone' => 'Europe/Paris',
            ])
            ->throw()
            ->json();

        $platform = Http::withToken($accessToken)
            ->get('https://api.pirsch.io/api/v1/statistics/platform', [
                'id' => config('services.pirsch.domain_id'),
                'from' => now()->subDays(31)->toDateString(),
                'to' => now()->subDay()->toDateString(),
                'timezone' => 'Europe/Paris',
            ])
            ->throw()
            ->json();

        return array_merge($overview, $platform);
    }
}
