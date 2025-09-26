<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    /** @use HasFactory<\Database\Factories\ListingFactory> */
    use HasFactory;

    protected static function booted() : void
    {
        static::creating(function (self $listing) {
            $listing->slug = Str::slug($listing->title . ' ' . $listing->company->name);
        });
    }

    protected function casts() : array
    {
        return [
            'technologies' => 'array',
            'how_to_apply' => 'array',
            'published_at' => 'date',
        ];
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
