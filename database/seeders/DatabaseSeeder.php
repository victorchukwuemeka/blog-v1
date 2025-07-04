<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Console\Commands\SyncVisitorsCommand;

class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        Storage::disk('public')->deleteDirectory('images/posts');

        User::factory()->create([
            'name' => 'Benjamin Crozat',
            'email' => 'hello@benjamincrozat.com',
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

        Artisan::call(SyncVisitorsCommand::class);
    }
}
