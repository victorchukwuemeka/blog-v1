<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function activity() : BelongsToMany
    {
        return $this
            ->belongsToMany(Post::class)
            ->withCount('comments')
            ->published()
            ->latest('published_at')
            ->limit(5);
    }
}
