<?php

namespace Database\Factories;

use App\Models\AuthorInfo;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthorBook>
 */
class AuthorBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::query()->inRandomOrder()->first()->id,
            'author_id' => AuthorInfo::query()->inRandomOrder()->first()->id
        ];
    }
}
