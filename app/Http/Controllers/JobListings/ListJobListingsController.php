<?php

namespace App\Http\Controllers\JobListings;

use Illuminate\View\View;
use App\Models\JobListing;
use App\Http\Controllers\Controller;

class ListJobListingsController extends Controller
{
    public function __invoke() : View
    {
        return view('job-listings.index', [
            'jobListings' => JobListing::query()
                ->latest()
                ->paginate(12),
        ]);
    }
}
