<?php

namespace App\Actions;

use App\Models\Category;
use OpenAI\Laravel\Facades\OpenAI;

class GenerateCategoryPage
{
    public function generate(Category $category, ?string $additionalInstructions = null) : Category
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.generate-category-page.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.generate-category-page.user', [
                            'category' => $category,
                            'additionalInstructions' => $additionalInstructions,
                        ])->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'category_page',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'content' => [
                                'type' => 'string',
                                'description' => 'The content of the category page.',
                            ],
                        ],
                        'required' => [
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

        $data = json_decode($response->outputText, true, JSON_THROW_ON_ERROR);

        $category->update([
            'content' => $data['content'],
        ]);

        return $category;
    }
}
