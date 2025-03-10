<?php

namespace Database\Seeders;

use App\Str;
use App\Models\Link;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use App\Actions\Posts\FetchPosts;
use Symfony\Component\Finder\SplFileInfo;

class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(10)->create();

        Link::factory(30)
            ->recycle($users)
            ->create();

        $slugs = app(FetchPosts::class)
            ->fetch()
            ->map(fn (SplFileInfo $file) => Str::slug(
                basename($file->getFilename(), '.md')
            ));

        Comment::factory(30)
            ->recycle($users)
            ->make()
            ->each(function (Comment $comment) use ($slugs) {
                $comment->post_slug = $slugs->random();
                $comment->save();
            });
    }
}
