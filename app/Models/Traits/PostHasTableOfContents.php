<?php

namespace App\Models\Traits;

use App\Markdown\TableOfContents;
use Illuminate\Support\HtmlString;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait PostHasTableOfContents
{
    public function toTableOfContents() : HtmlString
    {
        return new HtmlString(
            view('components.table-of-contents.index', [
                'items' => (new TableOfContents($this->content))->toArray()
            ])
            ->render()
        );
    }
}
