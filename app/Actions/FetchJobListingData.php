<?php

namespace App\Actions;

use Exception;
use App\Models\User;
use App\Models\Company;
use App\Models\JobListing;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use App\Notifications\JobListingFetched;

class FetchJobListingData
{
    public function fetch(string $url) : JobListing
    {
        if (Http::head($url)->failed()) {
            throw new Exception('The job listing could not be fetched properly.');
        }

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
                                'description' => 'The title of the job listing.',
                                'minLength' => 1,
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'The description of the job listing.',
                                'minLength' => 1,
                            ],
                            'technologies' => [
                                'type' => 'array',
                                'description' => 'The technologies required for the job listing.',
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
                            'locations' => [
                                'type' => 'array',
                                'description' => 'Array of locations (city and/or country). Can be empty if none are provided.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                                'minItems' => 0,
                            ],
                            'setting' => [
                                'type' => 'string',
                                'description' => 'Work setting: fully-remote, hybrid, or on-site.',
                                'enum' => [
                                    'fully-remote',
                                    'hybrid',
                                    'on-site',
                                ],
                            ],
                            'equity' => [
                                'type' => 'boolean',
                                'description' => 'Whether equity is offered for the role (true or false).',
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
                            'perks' => [
                                'type' => 'array',
                                'description' => 'Array of perks and benefits mentioned. Can be empty.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                                'minItems' => 0,
                            ],
                            'interview_process' => [
                                'type' => 'array',
                                'description' => 'Array describing the interview process steps. Can be empty.',
                                'items' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                ],
                                'minItems' => 0,
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
                            'description',
                            'technologies',
                            'how_to_apply',
                            'locations',
                            'setting',
                            'equity',
                            'min_salary',
                            'max_salary',
                            'currency',
                            'perks',
                            'interview_process',
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

        $company = Company::query()->updateOrCreate([
            'name' => $json->company->name,
        ], [
            'url' => $json->company->url,
            'logo' => $json->company->logo,
            'about' => $json->company->about,
        ]);

        $jobListing = JobListing::query()->updateOrCreate([
            'url' => $json->url,
        ], [
            'company_id' => $company->id,
            'source' => $json->source,
            'language' => $json->language,
            'title' => $json->title,
            'description' => $json->description,
            'technologies' => $json->technologies,
            'perks' => $json->perks ?? [],
            'locations' => $json->locations,
            'setting' => $json->setting,
            'min_salary' => $json->min_salary ?? 0,
            'max_salary' => $json->max_salary ?? 0,
            'currency' => $json->currency,
            'equity' => (bool) ($json->equity ?? false),
            'interview_process' => $json->interview_process ?? [],
            'how_to_apply' => $json->how_to_apply,
        ]);

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new JobListingFetched($jobListing));

        return $jobListing;
    }
}
