<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    public function handle() : void
    {
        $sitemap = Sitemap::create(config('app.url'));

        $sitemap->add(route('home'));

        $sitemap->add(route('posts.index'));

        Post::query()
            ->published()
            ->cursor()
            ->each(fn (Post $post) => $sitemap->add(route('posts.show', $post)));

        Category::query()
            ->cursor()
            ->each(fn (Category $category) => $sitemap->add(route('categories.show', $category)));

        $sitemap->add(route('links.index'));

        $sitemap->writeToFile($path = public_path('sitemap.xml'));

        $this->info("Sitemap generated successfully at $path");
    }
}
