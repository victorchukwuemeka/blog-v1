<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Jobs\RecommendPosts;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:generate-recommendations',
    description: 'Generate recommendations for posts'
)]
class GenerateRecommendationsCommand extends Command
{
    public function handle() : void
    {
        Post::query()
            ->published()
            ->cursor()
            ->each(function (Post $post) {
                RecommendPosts::dispatch($post);

                $this->info("Queued recommendation generation for \"$post->title\"…");
            });
    }
}
