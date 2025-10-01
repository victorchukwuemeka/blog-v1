<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Company;
use App\Models\JobListing;
use App\Notifications\JobListingFetched;

class CreateJobListing
{
    public function create(object $data) : JobListing
    {
        $company = Company::query()->updateOrCreate([
            'name' => $data->company->name,
        ], [
            'url' => $data->company->url,
            'logo' => $data->company->logo,
            'about' => $data->company->about,
        ]);

        $jobListing = JobListing::query()->updateOrCreate([
            'url' => $data->url,
        ], [
            'company_id' => $company->id,
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

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new JobListingFetched($jobListing));

        return $jobListing;
    }
}
