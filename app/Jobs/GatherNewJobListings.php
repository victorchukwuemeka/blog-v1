<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GatherNewJobListings implements ShouldQueue
{
    use Queueable;

    public function handle() : void
    {
        app(\App\Actions\GatherNewJobListings::class)->gather();
    }
}
