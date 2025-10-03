<?php

namespace App\Http\Controllers\Jobs;

use App\Models\Job;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ShowJobController extends Controller
{
    public function __invoke(Job $job) : View
    {
        return view('jobs.show', compact('job'));
    }
}
