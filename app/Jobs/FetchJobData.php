<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchJobData implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $url,
    ) {}

    public function handle(): void
    {
        app(\App\Actions\FetchJobData::class)->fetch($this->url);
    }
}
