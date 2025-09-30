<?php

namespace App\Models;

use App\Markdown\TableOfContents;
use Illuminate\Support\HtmlString;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function activity() : BelongsToMany
    {
        return $this
            ->belongsToMany(Post::class)
            ->published()
            ->orderBy('sessions_count', 'desc')
            ->limit(5);
    }

    public function readTime() : Attribute
    {
        return Attribute::make(
            fn () => ceil(str_word_count($this->content) / 200),
        )->shouldCache();
    }

    public function toTableOfContents() : HtmlString
    {
        return new HtmlString(
            view('components.table-of-contents.index', [
                'items' => new TableOfContents(<<< MD
$this->content

## All articles about $this->name
MD)->toArray(),
            ])->render()
        );
    }
}
