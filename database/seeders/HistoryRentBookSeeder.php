<?php

namespace Database\Seeders;

use App\Models\HistoryRentBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistoryRentBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistoryRentBook::factory()->count(10)->create();
    }
}
