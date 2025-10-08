<?php

namespace App\Actions;

use Exception;
use App\Models\Job;
use OpenAI\Laravel\Facades\OpenAI;

// The reason for this action to exist is to allow me to revise existing
// jobs. The prompts used below will evolve and so should the jobs.
class ReviseJob
{
    public function revise(Job $job, ?string $additionalInstructions = null) : Job
    {
        if (! $job->html) {
            throw new Exception('The job cannot be revised because it has no HTML content.');
        }

        $response = OpenAI::responses()->create([
            'model' => 'gpt-5-mini',
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.revise-job.developer')->render(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => view('components.prompts.revise-job.user', compact('job', 'additionalInstructions'))->render(),
                    ]],
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'job',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'language' => [
                                'type' => 'string',
                                'description' => "Language code of the job in ISO 639 format, for example 'en', 'fr', or 'de'.",
                                'pattern' => '^[a-zA-Z]{2,3}(-[a-zA-Z]{2,3})?$',
                                'minLength' => 2,
                            ],
                            'title' => [
                                'type' => 'string',
                                'description' => 'The title of the job.',
                                'minLength' => 1,
                            ],
                            'description' => [
                                'type' => 'string',
                                'description' => 'The description of the job.',
                                'minLength' => 1,
                            ],
                            'technologies' => [
                                'type' => 'array',
                                'description' => 'The technologies required for the job.',
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
                        ],
                        'required' => [
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
                        ],
                        'additionalProperties' => false,
                    ],
                ],
                'verbosity' => 'medium',
            ],
            'reasoning' => [
                'effort' => 'medium',
                'summary' => 'auto',
            ],
            'tools' => [[
                'type' => 'web_search_preview',
                'search_context_size' => 'medium',
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

        $data = json_decode($response->outputText ?? '', associative: false);

        return Job::query()->updateOrCreate([
            'url' => $data->url,
        ], [
            'source' => $data->source,
            'language' => $data->language,
            'title' => $data->title,
            'description' => $data->description,
            'technologies' => $data->technologies,
            'perks' => $data->perks ?? [],
            'locations' => $data->locations,
            'setting' => $data->setting,
            'min_salary' => $data->min_salary ?? 0,
            'max_salary' => $data->max_salary ?? 0,
            'currency' => $data->currency,
            'equity' => (bool) ($data->equity ?? false),
            'interview_process' => $data->interview_process ?? [],
            'how_to_apply' => $data->how_to_apply,
        ]);
    }
}
