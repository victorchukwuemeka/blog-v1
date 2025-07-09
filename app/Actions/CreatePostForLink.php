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

        $html = $response->body();

        app(Readability::class)->parse($html);

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
                        'author' => app(Readability::class)->getAuthor(),
                        'title' => app(Readability::class)->getTitle(),
                        'content' => app(Readability::class)->getContent(),
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
            'published_at' => $link->is_approved ?? now(),
        ]);

        $link->update([
            'post_id' => $post->id,
        ]);

        RecommendPosts::dispatch($post);

        return $post;
    }
}
