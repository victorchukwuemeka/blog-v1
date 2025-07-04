<?php

namespace App\Models;

use App\Str;
use Filament\Panel;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, MustVerifyEmail, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted() : void
    {
        static::creating(
            fn (User $user) => $user->slug = Str::slug($user->name)
        );
    }

    /**
     * @return array<string, string>
     */
    protected function casts() : array
    {
        return [
            'github_data' => 'array',
            'password' => 'hashed',
            'refreshed_at' => 'datetime',
        ];
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class)->published();
    }

    public function links() : HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function about() : Attribute
    {
        return Attribute::make(
            fn () => $this->biography ?? $this->github_data['user']['bio'] ?? '',
        );
    }

    public function blogUrl() : Attribute
    {
        return Attribute::make(
            fn () => $this->github_data['user']['blog'] ?? null,
        );
    }

    public function company() : Attribute
    {
        return Attribute::make(
            fn () => $this->github_data['user']['company'] ?? null,
        );
    }

    public function isAdmin() : bool
    {
        return 'benjamincrozat@me.com' === $this->email;
    }

    public function canAccessPanel(Panel $panel) : bool
    {
        return $this->isAdmin();
    }
}
