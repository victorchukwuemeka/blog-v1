<?php

namespace App\Actions;

use App\Models\Link;
use App\Models\Post;
use App\Jobs\RecommendPosts;
use OpenAI\Laravel\Facades\OpenAI;

class CreatePostForLink
{
    public function create(Link $link) : Post
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => view('components.prompts.create-post-for-link.system')->render(),
                ],
                [
                    'role' => 'user',
                    'content' => view('components.prompts.create-post-for-link.user', [
                        'url' => $link->url,
                        'title' => $link->title,
                        'author' => $link->author,
                        'description' => $link->description,
                        'notes' => $link->notes,
                    ])->render(),
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'post',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => [
                                'type' => 'string',
                                'description' => 'The title of the post.',
                            ],
                            'content' => [
                                'type' => 'string',
                                'description' => 'The content of the post.',
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'The meta description of the post. 160 characters or less.',
                            ],
                        ],
                        'required' => [
                            'title',
                            'content',
                            'description',
                        ],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
        ]);

        $json = json_decode($response->choices[0]->message->content);

        $post = Post::query()->create([
            'user_id' => 1,
            'title' => $json->title,
            'content' => $json->content,
            'description' => $json->description,
            'published_at' => now(),
        ]);

        $link->update([
            'post_id' => $post->id,
        ]);

        RecommendPosts::dispatch($post);

        return $post;
    }
}
