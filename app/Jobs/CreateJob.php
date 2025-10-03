<?php

namespace App\Jobs;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public object $data,
    ) {}

    public function handle(): void
    {
        app(\App\Actions\CreateJob::class)->create($this->data);
    }
}
