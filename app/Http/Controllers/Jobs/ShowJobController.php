<?php

namespace App\Http\Controllers\Jobs;

use Illuminate\View\View;
use App\Models\Job;
use App\Http\Controllers\Controller;

class ShowJobController extends Controller
{
    public function __invoke(Job $job): View
    {
        return view('jobs.show', compact('job'));
    }
}
