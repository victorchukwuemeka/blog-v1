<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    public function run() : void
    {
        Link::factory(30)
            ->recycle(User::all())
            ->approved()
            ->create();
    }
}
