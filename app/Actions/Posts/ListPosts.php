<?php

namespace App\Actions\Posts;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;

class ListPosts
{
    public function list() : Collection
    {
        return app(ListMarkdownFiles::class)
            ->fetch()
            ->map(fn (SplFileInfo $file) => app(ExpandPost::class)->expand(
                app(ParseMarkdownFile::class)->parse($file)
            ))
            ->sortByDesc('published_at');
    }
}
