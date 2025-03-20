<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Metric extends Model
{
    /** @use HasFactory<\Database\Factories\MetricFactory> */
    use HasFactory;

    protected static function booted() : void
    {
        // We should always return the latest metric. I'm not a
        // fan of global scopes, but this one is appropriate.
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }
}
