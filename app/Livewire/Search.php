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
        $query = collect(explode(' ', $this->query))
            ->filter()
            ->map(fn (string $word) => '+' . $word . '*')
            ->implode(' ');

        return view('livewire.search', [
            'posts' => blank($query)
                ? collect()
                : Post::search($query)->take(5)->get(),
            'links' => blank($query)
                ? collect()
                : Link::search($query)->take(5)->get(),
        ]);
    }
}
