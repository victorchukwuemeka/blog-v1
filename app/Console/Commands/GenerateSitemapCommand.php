<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:generate-sitemap',
    description: 'Generate the sitemap.'
)]
class GenerateSitemapCommand extends Command
{
    public function handle() : void
    {
        $sitemap = Sitemap::create(config('app.url'));

        $sitemap->add(route('home'));

        $sitemap->add(route('posts.index'));

        Post::query()
            ->published()
            ->cursor()
            ->each(fn (Post $post) => $sitemap->add(route('posts.show', $post)));

        User::query()
            ->cursor()
            ->each(fn (User $user) => $sitemap->add(route('authors.show', $user)));

        $sitemap->add(route('categories.index'));

        Category::query()
            ->cursor()
            ->each(fn (Category $category) => $sitemap->add(route('categories.show', $category)));

        $sitemap->add(route('links.index'));

        Listing::query()
            ->cursor()
            ->each(fn (Listing $listing) => $sitemap->add(route('listings.show', $listing)));

        $sitemap->add(route('links.index'));

        $sitemap->writeToFile($path = public_path('sitemap.xml'));

        $this->info("Sitemap generated successfully at $path");
    }
}
