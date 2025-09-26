<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    public function run() : void
    {
        Listing::factory(50)
            ->recycle(Company::all())
            ->create();
    }
}
