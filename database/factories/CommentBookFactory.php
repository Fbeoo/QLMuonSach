<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentBook>
 */
class CommentBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->sentence,
            'book_id' => Book::query()->inRandomOrder()->first()->id,
            'user_id' => User::query()->inRandomOrder()->first()->id
        ];
    }
}
