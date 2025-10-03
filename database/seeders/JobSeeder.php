<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Company;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run() : void
    {
        Job::factory(50)
            ->recycle(Company::all())
            ->create();
    }
}
