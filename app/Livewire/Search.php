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
        return view('livewire.search', [
            'posts' => empty($this->query)
                ? collect()
                : Post::search($this->query)
                    ->take(5)
                    ->get(),
            'links' => empty($this->query)
                ? collect()
                : Link::search($this->query)
                    ->take(5)
                    ->get(),
        ]);
    }
}
