<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Post;
use Livewire\Component;
use Illuminate\View\View;

class Search extends Component
{
    public string $query = '';

    public function render() : View
    {
        // Build a boolean search string for MySQL FULLTEXT search. By prefixing each word with '+' and suffixing with '*', we require every word to be present (AND logic) and allow for partial matches (wildcards). This makes search results both more relevant and more forgiving of incomplete words or typos, improving the user experience.
        $booleanQuery = collect(explode(' ', $this->query))
            ->filter()
            ->map(fn (string $word) => '+' . $word . '*')
            ->implode(' ');

        return view('livewire.search', [
            'posts' => blank($this->query)
                ? collect()
                : Post::query()
                    ->selectRaw(
                        'posts.*, MATCH(title, slug, content, description) AGAINST (? IN BOOLEAN MODE) AS relevance',
                        [$booleanQuery]
                    )
                    ->whereRaw(
                        'MATCH(title, slug, content, description) AGAINST (? IN BOOLEAN MODE)',
                        [$booleanQuery]
                    )
                    ->orderByDesc('relevance')
                    ->take(5)
                    ->get(),
            'links' => blank($this->query)
                ? collect()
                : Link::query()
                    ->selectRaw(
                        'links.*, MATCH(url, title, description) AGAINST (? IN BOOLEAN MODE) AS relevance',
                        [$booleanQuery]
                    )
                    ->whereRaw(
                        'MATCH(url, title, description) AGAINST (? IN BOOLEAN MODE)',
                        [$booleanQuery]
                    )
                    ->orderByDesc('relevance')
                    ->take(5)
                    ->get(),
        ]);
    }
}
