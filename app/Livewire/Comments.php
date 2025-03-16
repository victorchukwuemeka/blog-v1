<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\View\View;

class Comments extends Component
{
    public string $postSlug;

    public function render() : View
    {
        return view('livewire.comments', [
            'comments' => Comment::query()
                ->with('user')
                ->where('post_slug', $this->postSlug)
                ->whereNull('parent_id')
                ->paginate(30),
        ]);
    }
}
