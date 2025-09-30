<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncVisitorsCommand;
use App\Console\Commands\GenerateSitemapCommand;
use App\Console\Commands\RefreshUserDataCommand;
use App\Console\Commands\RefreshCategoryPagesCommand;

Schedule::command(GenerateSitemapCommand::class)
    ->daily();

Schedule::command(RefreshCategoryPagesCommand::class)
    ->daily();

Schedule::command(RefreshUserDataCommand::class)
    ->hourly();

Schedule::command(SyncVisitorsCommand::class)
    ->daily();
