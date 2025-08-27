<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    protected $with = ['post'];

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function revisions() : HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function title() : Attribute
    {
        return Attribute::make(
            fn () => "Report #$this->id for \"{$this->post->title}\"",
        );
    }
}
