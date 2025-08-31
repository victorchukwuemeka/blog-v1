<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Revision;
use App\Notifications\NewRevision;
use OpenAI\Laravel\Facades\OpenAI;

class RevisePost
{
    public function revise(Post $post, Report $report, ?string $additionalInstructions)
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.revise-post.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.revise-post.user', [
                            'post' => $post,
                            'report' => $report,
                            'additionalInstructions' => $additionalInstructions,
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
                                'description' => 'The title of the blog post.',
                            ],
                            'serp_title' => [
                                'type' => 'string',
                                'description' => "A title optimized for CTR on Google's SERP.",
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => "A description of the blog post optimized for CTR on Google's SERP.",
                            ],
                            'content' => [
                                'type' => 'string',
                                'description' => 'The content of the blog post. Do not include the title, Front Matter, or any JSON-LD/structured data.',
                            ],
                        ],
                        'required' => [
                            'title',
                            'serp_title',
                            'description',
                            'content',
                        ],
                        'additionalProperties' => false,
                    ],
                ],
                'verbosity' => 'high',
            ],
            'reasoning' => [
                'effort' => 'high',
                'summary' => 'auto',
            ],
            'tools' => [[
                'type' => 'web_search_preview',
                'search_context_size' => 'high',
                'user_location' => [
                    'type' => 'approximate',
                    'country' => 'US',
                ],
            ]],
            'store' => true,
        ]);

        $raw = json_decode($response->outputText, true) ?: [];

        // Keep only the expected keys
        $data = [
            'title' => isset($raw['title']) && is_string($raw['title']) ? trim($raw['title']) : '',
            'serp_title' => isset($raw['serp_title']) && is_string($raw['serp_title']) ? trim($raw['serp_title']) : '',
            'description' => isset($raw['description']) && is_string($raw['description']) ? trim($raw['description']) : '',
            'content' => isset($raw['content']) && is_string($raw['content']) ? $raw['content'] : '',
        ];

        // Sanitize content: remove JSON-LD and front matter; ensure title is not repeated.
        $content = $data['content'] ?? '';

        // Remove <script type="application/ld+json"> blocks
        $content = preg_replace('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>.*?<\/script>/is', '', $content);

        // Remove JSON-LD code fences ```json ... ``` if present
        $content = preg_replace('/```\s*json\s*[\r\n]+\{[\s\S]*?\}[\r\n]+```/i', '', $content);

        // Remove YAML/TOML front matter at the very top
        $content = preg_replace('/^---[\s\S]*?---\s*/', '', $content);

        // Remove leading H1 repeating the title (Markdown, HTML, or Setext)
        if ('' !== $data['title']) {
            $t = preg_quote($data['title'], '/');
            // Markdown H1: # Title
            $content = preg_replace('/^\s*#\s*' . $t . '\s*(?:\n|$)/u', '', $content, 1);
            // HTML H1
            $content = preg_replace('/^\s*<h1[^>]*>\s*' . $t . '\s*<\/h1>\s*(?:\n|$)/iu', '', $content, 1);
            // Setext H1
            $content = preg_replace('/^\s*' . $t . '\s*\n=+\s*(?:\n|$)/u', '', $content, 1);
        }

        $data['content'] = trim($content);

        $revision = Revision::query()->create([
            'report_id' => $report->id,
            'data' => $data,
        ]);

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new NewRevision($revision));

        return $revision;
    }
}
