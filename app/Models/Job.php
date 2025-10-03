<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    // Avoid conflict with the already existing jobs table.
    protected $table = 'job_listings';

    protected static function booted(): void
    {
        static::creating(function (self $listing) {
            $listing->slug = Str::slug($listing->title . ' ' . $listing->company->name);
        });
    }

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'locations' => 'array',
            'how_to_apply' => 'array',
            'perks' => 'array',
            'interview_process' => 'array',
            'equity' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
