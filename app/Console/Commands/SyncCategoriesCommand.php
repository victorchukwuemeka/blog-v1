<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// This is a temporary command that I'll remove once I'm in production.
class SyncCategoriesCommand extends Command
{
    protected $signature = 'app:sync-categories';

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
                $post = DB::connection('legacy')
                    ->table('posts')
                    ->where('id', $categoryPost->post_id)
                    ->first();

                if ($post) {
                    DB::table('category_post')->insert([
                        'category_id' => $categoryPost->category_id,
                        'post_slug' => $post->slug,
                    ]);

                    $this->info("Associated category ID $categoryPost->category_id with post slug $post->slug");
                }
            });

        $this->info('All categories have been synced and associated with posts.');
    }
}
