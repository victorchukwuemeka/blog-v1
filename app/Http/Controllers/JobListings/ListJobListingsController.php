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
            'companies' => Company::query()
                ->inRandomOrder()
                ->where('is_highlighted', true)
                ->limit(10)
                ->get(),

            'jobListings' => JobListing::query()
                ->latest()
                ->paginate(12),
        ]);
    }
}
