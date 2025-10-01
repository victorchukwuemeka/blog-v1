<?php

namespace App\Http\Controllers\JobListings;

use Illuminate\View\View;
use App\Models\JobListing;
use App\Http\Controllers\Controller;

class ShowJobListingController extends Controller
{
    public function __invoke(JobListing $jobListing) : View
    {
        return view('job-listings.show', compact('jobListing'));
    }
}
