<?php

use App\Models\Post;
use App\Actions\RecommendPosts;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

it('updates a post with GPT recommendations', function () {
    // The post we want recommendations for.
    $post = Post::factory()->create();

    // Two published posts that could be recommended.
    $firstRecommended = Post::factory()->create();
    $secondRecommended = Post::factory()->create();

    OpenAI::fake([
        CreateResponse::fake([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'recommendations' => [
                                [
                                    'id' => $firstRecommended->id,
                                    'title' => $firstRecommended->title,
                                    'reason' => 'Shares a similar topic.',
                                ],
                                [
                                    'id' => $secondRecommended->id,
                                    'title' => $secondRecommended->title,
                                    'reason' => 'Provides additional insights.',
                                ],
                            ],
                        ]),
                    ],
                ],
            ],
        ]),
    ]);

    app(RecommendPosts::class)->recommend($post);

    $post->refresh();

    // The recommendations were stored on the post.
    expect($post->recommendations)
        ->toHaveCount(2)
        ->and($post->recommendations->pluck('id')->all())
        ->toMatchArray([
            $firstRecommended->id,
            $secondRecommended->id,
        ]);

    // The helper attribute returns the actual Post models enriched with reasons.
    expect($post->recommendedPosts)
        ->toHaveCount(2)
        ->and($post->recommendedPosts->pluck('id')->all())->toMatchArray([
            $firstRecommended->id,
            $secondRecommended->id,
        ])
        ->and($post->recommendedPosts->first()->reason)
        ->toBeString();
});
