<?php

namespace App\Models\Traits;

use Laravel\Scout\Searchable;

/**
 * @mixin \App\Models\Link
 */
trait LinkSearchable
{
    use Searchable;

    public function toSearchableArray() : array
    {
        return [
            'user_name' => $this->user->name,
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function shouldBeSearchable() : bool
    {
        return $this->isApproved();
    }
}
