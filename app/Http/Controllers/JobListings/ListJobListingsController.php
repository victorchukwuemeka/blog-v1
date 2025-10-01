<?php

namespace App\Http\Controllers\JobListings;

use App\Models\Company;
use Illuminate\View\View;
use App\Models\JobListing;
use App\Http\Controllers\Controller;

class ListJobListingsController extends Controller
{
    public function __invoke() : View
    {
        return view('job-listings.index', [
            'companyLogos' => Company::query()
                ->inRandomOrder()
                ->limit(10)
                ->get()
                ->map(fn (Company $company) => $company->logo),

            'jobListings' => JobListing::query()
                ->latest()
                ->whereTrue('is_highlighted')
                ->paginate(12),
        ]);
    }
}
