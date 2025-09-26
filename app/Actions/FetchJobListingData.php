<?php

namespace App\Actions;

use App\Models\Company;
use App\Models\Listing;
use OpenAI\Laravel\Facades\OpenAI;

class FetchJobListingData
{
    public function fetch(string $url) : Listing
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-5',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.fetch-job-listing-data.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.fetch-job-listing-data.user', compact('url'))->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'job_listing',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => [
                                'type' => 'string',
                                'description' => 'Direct link to the job posting.',
                                'minLength' => 1,
                            ],
                            'language' => [
                                'type' => 'string',
                                'description' => "Language code of the original job posting in ISO 639 format, for example 'en', 'fr', or 'de'.",
                                'pattern' => '^[a-zA-Z]{2,3}(-[a-zA-Z]{2,3})?$',
                                'minLength' => 2,
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'A title in the original language of the listing.',
                                'minLength' => 1,
                            ],
                            'content' => [
                                'type' => 'string',
                                'description' => 'Strict description of the job responsibilities and core requirements in the original language of the listing. Must not omit the most important details. Use a 6th grade reading level.',
                                'minLength' => 1,
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'A short description of the job responsibilities and core requirements in the original language of the listing. Use a 6th grade reading level.',
                                'minLength' => 1,
                            ],
                            'technologies' => [
                                'type' => 'array',
                                'description' => 'Array of languages and frameworks required, spelled according to official branding guidelines (e.g. JavaScript, React, Node.js).',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                            ],
                            'how_to_apply' => [
                                'type' => 'array',
                                'description' => 'Step-by-step instructions to apply for this job.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                            ],
                            'location' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'description' => 'City and/or country for the job.',
                                        'minLength' => 1,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if location is not provided.',
                                    ],
                                ],
                            ],
                            'setting' => [
                                'type' => 'string',
                                'description' => 'Work setting: remote, hybrid, or on-site.',
                                'enum' => [
                                    'remote',
                                    'hybrid',
                                    'on-site',
                                ],
                            ],
                            'min_salary' => [
                                'anyOf' => [
                                    [
                                        'type' => 'number',
                                        'description' => 'Minimum salary if provided.',
                                        'minimum' => 0,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if no minimum salary is provided.',
                                    ],
                                ],
                            ],
                            'max_salary' => [
                                'anyOf' => [
                                    [
                                        'type' => 'number',
                                        'description' => 'Maximum salary if provided.',
                                        'minimum' => 0,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if no maximum salary is provided.',
                                    ],
                                ],
                            ],
                            'currency' => [
                                'anyOf' => [
                                    [
                                        'type' => 'string',
                                        'description' => 'The currency used for the salary.',
                                        'minLength' => 1,
                                    ],
                                    [
                                        'type' => 'null',
                                        'description' => 'Null if no currency is provided.',
                                    ],
                                ],
                            ],
                            'published_on' => [
                                'type' => 'string',
                                'description' => 'Date the job was published, as an ISO 8601 calendar date (YYYY-MM-DD).',
                                'pattern' => '^\d{4}-\d{2}-\d{2}$',
                            ],
                            'company' => [
                                '$ref' => '#/$defs/company',
                            ],
                            'source' => [
                                'type' => 'string',
                                'description' => 'Name of the website or source where this job was found.',
                                'minLength' => 1,
                            ],
                        ],
                        'required' => [
                            'url',
                            'language',
                            'title',
                            'content',
                            'description',
                            'technologies',
                            'how_to_apply',
                            'location',
                            'setting',
                            'min_salary',
                            'max_salary',
                            'currency',
                            'published_on',
                            'company',
                            'source',
                        ],
                        'additionalProperties' => false,
                        '$defs' => [
                            'company' => [
                                'type' => 'object',
                                'description' => 'Information about the company offering the job',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'description' => 'Exact name of the company.',
                                        'minLength' => 1,
                                    ],
                                    'url' => [
                                        'anyOf' => [
                                            [
                                                'type' => 'string',
                                                'description' => 'Official company website or profile.',
                                                'minLength' => 1,
                                            ],
                                            [
                                                'type' => 'null',
                                                'description' => 'Null if no company URL is provided.',
                                            ],
                                        ],
                                    ],
                                    'logo' => [
                                        'anyOf' => [
                                            [
                                                'type' => 'string',
                                                'description' => 'An URL to the company logo image.',
                                                'minLength' => 1,
                                            ],
                                            [
                                                'type' => 'null',
                                                'description' => 'Null if no company logo is provided.',
                                            ],
                                        ],
                                    ],
                                    'about' => [
                                        'type' => 'string',
                                        'description' => 'What the company is about, based on web research. Include founding year, domain, notable products, and mission. Whatever you can find.',
                                        'minLength' => 1,
                                    ],
                                ],
                                'required' => [
                                    'name',
                                    'url',
                                    'logo',
                                    'about',
                                ],
                                'additionalProperties' => false,
                            ],
                        ],
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
            'include' => [
                'reasoning.encrypted_content',
                'web_search_call.action.sources',
            ],
        ]);

        $json = json_decode($response->outputText ?? '', false);

        $company = Company::query()->firstOrCreate([
            'name' => $json->company->name,
        ], [
            'url' => $json->company->url,
            'logo' => $json->company->logo,
            'about' => $json->company->about,
        ]);

        return Listing::query()->firstOrCreate([
            'url' => $json->url,
        ], [
            'company_id' => $company->id,
            'source' => $json->source,
            'language' => $json->language,
            'title' => $json->title,
            'content' => $json->content,
            'description' => $json->description,
            'technologies' => $json->technologies,
            'location' => $json->location,
            'setting' => $json->setting,
            'min_salary' => $json->min_salary ?? 0,
            'max_salary' => $json->max_salary ?? 0,
            'currency' => $json->currency,
            'how_to_apply' => $json->how_to_apply,
            'published_at' => $json->published_on,
        ]);
    }
}
