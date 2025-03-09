<?php

namespace Database\Factories;

use App\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        $email = fake()->unique()->safeEmail();

        return [
            'name' => fake()->name(),
            'github_login' => fake()->userName(),
            'avatar' => fake()->imageUrl(),
            'github_data' => [
                'id' => fake()->unique()->randomNumber(),
                'name' => fake()->name(),
                'user' => [
                    'bio' => fake()->paragraph(),
                    'blog' => fake()->url(),
                    'company' => fake()->company(),
                    'html_url' => fake()->url(),
                    'login' => fake()->userName(),
                ],
                'email' => $email,
            ],
            'email' => $email,
            'remember_token' => Str::random(10),
        ];
    }
}
