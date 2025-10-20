<?php

namespace App\Models\Traits;

use Laravel\Scout\Searchable;

/**
 * @mixin \App\Models\Post
 */
trait PostSearchable
{
    use Searchable;
    

    public function toSearchableArray() : array
    {
        return [
            'user_name' => $this->user->name,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'description' => $this->description,
            'categories' => $this->categories->pluck('name')->toArray(),
        ];
    }

    public function shouldBeSearchable() : bool
    {
        return $this->isPublished();
    }
}
