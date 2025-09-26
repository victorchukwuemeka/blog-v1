<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {

        return [
            'company_id' => Company::factory(),
            'url' => fake()->url(),
            'source' => fake()->word(),
            'language' => fake()->languageCode(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(random_int(3, 10), true),
            'description' => fake()->paragraph(),
            'technologies' => fake()->words(random_int(3, 10)),
            'location' => fake()->city() . ', ' . fake()->country(),
            'setting' => collect(['remote', 'hybrid', 'on-site'])->random(),
            'min_salary' => $minSalary = fake()->numberBetween(10000, 100000),
            'max_salary' => fake()->numberBetween($minSalary, $minSalary * random_int(2, 4)),
            'currency' => fake()->currencyCode(),
            'how_to_apply' => fake()->sentences(random_int(2, 5)),
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
