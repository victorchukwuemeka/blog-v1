<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncVisitorsCommand;
use App\Console\Commands\IngestJobFeedsCommand;
use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\RefreshUserDataCommand;
use App\Console\Commands\RefreshCategoryPagesCommand;

Schedule::command(GenerateSitemapCommand::class)
    ->daily()
    ->thenPing('https://heartbeats.laravel.com/01k6k3d1ddxcz9abxwfmwpjr1b/ping');

Schedule::command(IngestJobFeedsCommand::class)
    ->daily()
    ->at('01:00')
    ->withoutOverlapping();

Schedule::command(RefreshCategoryPagesCommand::class)
    ->daily()
    ->thenPing('https://heartbeats.laravel.com/01k6k3ee5z6nc7tabyyqjgsbbe/ping');

Schedule::command(RefreshUserDataCommand::class)
    ->hourly()
    ->withoutOverlapping()
    ->thenPing('https://heartbeats.laravel.com/01k6k3f3ab25ggctkxe32emphj/ping');

Schedule::command(SyncVisitorsCommand::class)
    ->daily()
    ->thenPing('https://heartbeats.laravel.com/01k6k3gfq8307hv2689hh8zjsm/ping');
