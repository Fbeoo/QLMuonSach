<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoryRentBook>
 */
class HistoryRentBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rent_date' => now(),
            'status' => rand(0,1),
            'total_price' => 100000,
            'user_id' => User::query()->inRandomOrder()->first()->id
        ];
    }
}
