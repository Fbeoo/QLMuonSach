<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthorInfo>
 */
class AuthorInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_name' => fake()->name,
            'dob' => fake()->dateTimeBetween('1920-01-01','2000-01,-01'),
            'address' => fake()->country
        ];
    }
}
