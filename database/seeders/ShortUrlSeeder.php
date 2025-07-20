<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShortUrlSeeder extends Seeder
{
    public function run() : void
    {
        \App\Models\ShortUrl::factory()->count(10)->create();
    }
}
