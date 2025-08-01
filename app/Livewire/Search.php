<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Post;
use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;

// Everything related to search has been written by o3.
// I have absolutely no idea what's going on, haha.
class Search extends Component
{
    public string $query = '';

    public function render() : View
    {
        $tokens = collect(explode(' ', trim($this->query)))->filter();

        // MySQL ignores terms shorter than ft_min_word_len (4 by default). We split terms into those that can be indexed and those that are too short (or purely numeric) which we will handle separately with LIKE filters.
        $longTerms = $tokens->filter(fn (string $word) => mb_strlen($word) >= 3);
        $shortTerms = $tokens->diff($longTerms);

        // Build BOOLEAN query from long terms only.
        $booleanQuery = $longTerms
            ->map(fn (string $word) => '+' . $word . '*')
            ->implode(' ');

        return view('livewire.search', [
            'posts' => blank($this->query)
                ? collect()
                : Post::query()
                    ->when($longTerms->isNotEmpty(), function (Builder $query) use ($booleanQuery) {
                        $query
                            ->selectRaw(
                                'posts.*, MATCH(title, slug, content, description) AGAINST (? IN BOOLEAN MODE) AS relevance',
                                [$booleanQuery]
                            )
                            ->whereRaw(
                                'MATCH(title, slug, content, description) AGAINST (? IN BOOLEAN MODE)',
                                [$booleanQuery]
                            )
                            ->orderByDesc('relevance');
                    })
                    ->when($shortTerms->isNotEmpty(), function (Builder $query) use ($shortTerms) {
                        $query->where(function (Builder $query) use ($shortTerms) {
                            foreach ($shortTerms as $term) {
                                $pattern = '%' . $term . '%';

                                $query->where(function (Builder $query) use ($pattern) {
                                    $query
                                        ->where('title', 'like', $pattern)
                                        ->orWhere('slug', 'like', $pattern)
                                        ->orWhere('content', 'like', $pattern)
                                        ->orWhere('description', 'like', $pattern);
                                });
                            }
                        });
                    })
                    ->take(5)
                    ->get(),
            'links' => blank($this->query)
                ? collect()
                : Link::query()
                    ->when($longTerms->isNotEmpty(), function (Builder $query) use ($booleanQuery) {
                        $query
                            ->selectRaw(
                                'links.*, MATCH(url, title, description) AGAINST (? IN BOOLEAN MODE) AS relevance',
                                [$booleanQuery]
                            )
                            ->whereRaw(
                                'MATCH(url, title, description) AGAINST (? IN BOOLEAN MODE)',
                                [$booleanQuery]
                            )
                            ->orderByDesc('relevance');
                    })
                    ->when($shortTerms->isNotEmpty(), function (Builder $query) use ($shortTerms) {
                        $query->where(function (Builder $query) use ($shortTerms) {
                            foreach ($shortTerms as $term) {
                                $pattern = '%' . $term . '%';

                                $query->where(function (Builder $query) use ($pattern) {
                                    $query
                                        ->where('title', 'like', $pattern)
                                        ->orWhere('url', 'like', $pattern)
                                        ->orWhere('description', 'like', $pattern);
                                });
                            }
                        });
                    })
                    ->take(5)
                    ->get(),
        ]);
    }
}
