<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SyncVisitorsCommand;

Schedule::command(SyncVisitorsCommand::class)
    ->dailyAt('01:00')
    ->timezone('Europe/Paris');
