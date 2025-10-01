<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchJobListingData implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $url,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\FetchJobListingData::class)->fetch($this->url);
    }
}
