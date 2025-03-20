<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncAnalyticsCommand;

Schedule::command(SyncAnalyticsCommand::class)
    ->dailyAt('01:00')
    ->timezone('Europe/Paris');
