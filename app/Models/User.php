<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, MustVerifyEmail, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts() : array
    {
        return [
            'github_data' => 'array',
            'password' => 'hashed',
        ];
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function links() : HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function canAccessPanel(Panel $panel) : bool
    {
        return 'hello@benjamincrozat.com' === $this->email;
    }
}
