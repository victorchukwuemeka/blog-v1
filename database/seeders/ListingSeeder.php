<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    public function run() : void
    {
        JobListing::factory(50)
            ->recycle(Company::all())
            ->create();
    }
}
