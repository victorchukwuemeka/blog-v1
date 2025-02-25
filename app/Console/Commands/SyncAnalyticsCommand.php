<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncAnalyticsCommand extends Command
{
    protected $signature = 'app:sync-analytics';

    protected $description = 'Fetch fresh numbers from the analytics provider';

    public function handle() : void
    {
        $data = $this->fetch();

        cache()->put('platform_desktop', ($data['relative_platform_desktop'] ?? 0) * 100);
        cache()->put('sessions', $data['sessions'] ?? 0);
        cache()->put('views', $data['views'] ?? 0);
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
