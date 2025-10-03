<?php

namespace App\Jobs;

use App\Scraper\Webpage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchJobData implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Webpage $webpage,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\FetchJobData::class)->fetch($this->webpage);
    }
}
