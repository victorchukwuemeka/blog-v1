<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Attribute\AsCommand;

// This is a temporary command that I'll remove once I'm in production.
#[AsCommand(name: 'app:sync-categories')]
class SyncCategoriesCommand extends Command
{
    protected $description = 'Fetch categories from the legacy database';

    public function handle() : void
    {
        $this->info('Syncing categories…');

        DB::connection('legacy')
            ->table('categories')
            ->get()
            ->each(function (object $legacyCategory) {
                Category::query()->updateOrCreate([
                    'id' => $legacyCategory->id,
                ], [
                    'name' => $legacyCategory->name,
                    'slug' => $legacyCategory->slug,
                ]);

                $this->info("Synced category \"$legacyCategory->name\"");
            });

        DB::connection('legacy')
            ->table('category_post')
            ->get()
            ->each(function (object $categoryPost) {
                DB::table('category_post')->updateOrInsert([
                    'id' => $categoryPost->id,
                ], [
                    'category_id' => $categoryPost->category_id,
                    'post_id' => $categoryPost->post_id,
                ]);

                $this->info("Associated category ID $categoryPost->category_id with post ID $categoryPost->post_id.");
            });

        $this->info('All categories have been synced and associated with posts.');

        $this->info('Checking for categories without posts…');

        $withoutPosts = Category::query()
            ->whereDoesntHave('posts')
            ->get();

        if ($withoutPosts->count()) {
            $this->info('Found ' . $withoutPosts->count() . ' categories without posts. Removing them…');

            $withoutPosts->each(function (Category $category) {
                $this->info("Removing category \"{$category->name}\"");

                $category->delete();
            });
        } else {
            $this->info('No categories without posts found.');
        }
    }
}
