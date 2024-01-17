<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\HistoryRentBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailHistoryRentBook>
 */
class DetailHistoryRentBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expiration_date' => fake()->dateTimeBetween('2024/3/17','2024/4/17'),
            'quantity' => rand(1,3),
            'return_date' => fake()->dateTimeBetween('2024/1/17','2024/3/17'),
            'status' => rand(0,1),
            'book_id' => Book::query()->inRandomOrder()->first()->id,
            'history_rent_book_id' => HistoryRentBook::query()->inRandomOrder()->first()->id
        ];
    }
}
