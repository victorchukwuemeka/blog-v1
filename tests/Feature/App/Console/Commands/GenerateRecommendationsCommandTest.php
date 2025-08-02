<?php

use App\Models\Post;
use App\Jobs\RecommendPosts;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Bus;
use App\Console\Commands\GenerateRecommendationsCommand;

it('queues a recommendation job for a specific post when a slug is provided', function () {
    $post = Post::factory()->create(['slug' => 'my-slug']);

    Bus::fake();

    artisan(GenerateRecommendationsCommand::class, ['slug' => 'my-slug'])
        ->assertSuccessful();

    Bus::assertDispatched(RecommendPosts::class, function (RecommendPosts $job) use ($post) {
        return $job->post->is($post);
    });
});

it('queues recommendation jobs for all published and non-commercial posts when no slug is provided', function () {
    // Three published posts (default factory sets published_at).
    $eligible = Post::factory(3)->create([
        'is_commercial' => false,
    ]);

    // One commercial post that should be ignored.
    Post::factory()->create([
        'is_commercial' => true,
    ]);

    // One unpublished post that should be ignored
    Post::factory()->create(['published_at' => null]);

    Bus::fake();

    artisan(GenerateRecommendationsCommand::class)
        ->assertSuccessful();

    Bus::assertDispatchedTimes(RecommendPosts::class, $eligible->count());
});
