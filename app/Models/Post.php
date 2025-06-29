<?php

namespace App\Models;

use App\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Support\Collection;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements Feedable
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    protected $withCount = ['comments'];

    public static function booted() : void
    {
        static::creating(
            fn (Post $post) => $post->slug ??= Str::slug($post->title)
        );
    }

    protected function casts() : array
    {
        return [
            'published_at' => 'datetime',
            'modified_at' => 'datetime',
            'recommendations' => 'collection',
        ];
    }

    #[Scope]
    protected function published(Builder $query) : void
    {
        $query->whereNotNull('published_at');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function formattedContent() : Attribute
    {
        return Attribute::make(
            fn () => Str::markdown($this->content),
        )->shouldCache();
    }

    public function imageUrl() : Attribute
    {
        return Attribute::make(
            fn () => $this->hasImage() ? Storage::disk($this->image_disk)->url($this->image_path) : null,
        )->shouldCache();
    }

    public function readTime() : Attribute
    {
        return Attribute::make(
            fn () => ceil(str_word_count($this->content) / 200),
        )->shouldCache();
    }

    public function recommendedPosts() : Attribute
    {
        return Attribute::make(
            fn () => empty($this->recommendations) ? null : Post::query()
                ->whereIn('id', $this->recommendations->pluck('id'))
                ->get()
                ->map(function (self $post) {
                    $recommendation = collect($this->recommendations)
                        ->firstWhere('id', $post->id);

                    if (! empty($recommendation['reason'])) {
                        $post->reason = $recommendation['reason'];
                    }

                    return $post;
                }),
        )->shouldCache();
    }

    public function hasImage() : bool
    {
        return $this->image_path && $this->image_disk;
    }

    public static function getFeedItems() : Collection
    {
        return static::query()
            ->published()
            ->latest('published_at')
            ->limit(50)
            ->get();
    }

    public function toFeedItem() : FeedItem
    {
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary(Str::markdown($this->description ?? ''))
            ->updated($this->modified_at ?? $this->published_at)
            ->link(route('posts.show', $this))
            ->authorName($this->user->name);
    }
}
