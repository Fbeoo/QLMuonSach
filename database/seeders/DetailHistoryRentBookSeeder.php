<?php

namespace Database\Seeders;

use App\Models\DetailHistoryRentBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailHistoryRentBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailHistoryRentBook::factory()->count(20)->create();
    }
}
