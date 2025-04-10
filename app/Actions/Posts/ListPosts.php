<?php

namespace App\Actions\Posts;

use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPosts
{
    public function list() : LengthAwarePaginator
    {
        return app(ListMarkdownFiles::class)
            ->fetch()
            ->map(fn (SplFileInfo $file) => app(ExpandPost::class)->expand(
                app(ParseMarkdownFile::class)->parse($file)
            ))
            ->sortByDesc('published_at')
            ->paginate(24);
    }
}
