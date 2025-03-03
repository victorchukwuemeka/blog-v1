<?php

namespace App\Actions\Posts;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class FetchPosts
{
    public function fetch() : Collection
    {
        return collect(
            iterator_to_array(
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
