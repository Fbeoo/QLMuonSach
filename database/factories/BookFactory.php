<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Cuộc phiêu lưu của ' . fake()->name,
            'year_publish' => rand(1980,2000),
            'price_rent' => 20000,
            'weight'=> rand(100,300),
            'total_page' => rand(100,500),
            'thumbnail' => 'image.jpg',
            'status' => 1
        ];
    }
}
