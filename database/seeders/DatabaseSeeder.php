<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(10)->create();

        Post::factory(30)
            ->recycle($users)
            ->withCategories()
            ->withComments()
            ->create();

        Link::factory(30)
            ->recycle($users)
            ->approved()
            ->create();
    }
}
