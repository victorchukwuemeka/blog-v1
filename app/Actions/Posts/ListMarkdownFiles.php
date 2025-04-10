<?php

namespace App\Actions\Posts;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class ListMarkdownFiles
{
    public function fetch() : Collection
    {
        return collect(
            iterator_to_array(
                // This is a simple way to fetch all the posts from the markdown folder.
                app(Finder::class)
                    ->files()
                    ->in(resource_path('markdown/posts'))
                    ->name('*.md')
                    ->sortByName(),
                false
            )
        );
    }
}
