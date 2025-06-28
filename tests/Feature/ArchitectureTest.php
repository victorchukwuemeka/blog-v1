<?php

arch()
    ->preset()
    ->laravel()
    ->ignoring([
        'App\Providers\Filament',
        // I have no idea what's going on with commands, but I get weird errors.
        'App\Console\Commands',
    ]);
