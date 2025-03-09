<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Link extends Model
{
    /** @use HasFactory<\Database\Factories\LinkFactory> */
    use HasFactory;

    protected function casts() : array
    {
        return [
            'is_approved' => 'datetime',
            'is_declined' => 'datetime',
        ];
    }

    public function scopeApproved(Builder $query) : void
    {
        $query
            ->whereNotNull('is_approved')
            ->whereNull('is_declined');
    }

    public function scopeDeclined(Builder $query) : void
    {
        $query
            ->whereNotNull('is_declined')
            ->whereNull('is_approved');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Clear cache when the model is saved, created, updated, or deleted
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('distinct-users-count');
        });

        static::deleted(function () {
            Cache::forget('distinct-users-count');
        });
    }
}
