<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RecommendPosts implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\RecommendPosts::class)->recommend($this->post);
    }
}
