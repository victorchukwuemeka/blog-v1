<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RevisePost implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
        public Report $report,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\RevisePost::class)->revise($this->post, $this->report);
    }
}
