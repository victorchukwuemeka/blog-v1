<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
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
}
