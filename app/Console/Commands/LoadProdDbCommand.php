<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:load-prod-db',
    description: 'Generate recommendations for posts'
)]
class LoadProdDbCommand extends Command
{
    public function handle() : void
    {
        Artisan::call('snapshot:create', [
            '--connection' => 'production',
        ]);

        Artisan::call('snapshot:load');
    }
}
