<?php

namespace App\Actions;

use App\Models\Link;
use App\Models\Post;
use App\Jobs\RecommendPosts;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use fivefilters\Readability\Readability;

class CreatePostForLink
{
    public function create(Link $link) : Post
    {
        $response = Http::get($link->url)->throw();

        app(Readability::class)->parse(
            $response->body()
        );

        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.create-post-for-link.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.create-post-for-link.user', [
                            'url' => $link->url,
                            'notes' => $link->notes,
                        ])->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
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
                ],
                'verbosity' => 'low',
            ],
            'reasoning' => [
                'effort' => 'low',
                'summary' => 'auto',
            ],
            'tools' => [[
                'type' => 'web_search_preview',
                'search_context_size' => 'low',
                'user_location' => [
                    'type' => 'approximate',
                    'country' => 'US',
                ],
            ]],
            'store' => true,
        ]);

        $json = json_decode($response->outputText);

        $post = Post::query()->create([
            'user_id' => 1,
            'title' => $json->title,
            'content' => $json->content,
            'description' => $json->description,
            'published_at' => $link->is_approved ?? now(),
        ]);

        $link->update([
            'post_id' => $post->id,
        ]);

        RecommendPosts::dispatch($post);

        return $post;
    }
}
