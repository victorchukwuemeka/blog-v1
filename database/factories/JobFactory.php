<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'company_id' => Company::factory(),
            'url' => fake()->url(),
            'source' => fake()->word(),
            'language' => fake()->languageCode(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'technologies' => fake()->words(random_int(3, 10)),
            'locations' => Collection::times(
                random_int(1, 2),
                fn() => fake()->city() . ', ' . fake()->country(),
            ),
            'setting' => collect(['fully-remote', 'hybrid', 'on-site'])->random(),
            'min_salary' => $minSalary = fake()->numberBetween(10000, 100000),
            'max_salary' => fake()->numberBetween($minSalary, $minSalary * random_int(2, 4)),
            'currency' => fake()->currencyCode(),
            'equity' => fake()->boolean(),
            'how_to_apply' => fake()->sentences(random_int(2, 5)),
            'perks' => fake()->optional()->sentences(random_int(0, 4)) ?? [],
            'interview_process' => fake()->optional()->sentences(random_int(0, 4)) ?? [],
        ];
    }
}
