<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncVisitorsCommand;
use App\Console\Commands\GenerateSitemapCommand;

Schedule::command(GenerateSitemapCommand::class)
    ->daily();

Schedule::command(SyncVisitorsCommand::class)
    ->daily();
