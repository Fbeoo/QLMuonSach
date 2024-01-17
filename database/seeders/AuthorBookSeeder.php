<?php

namespace Database\Seeders;

use App\Models\AuthorBook;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AuthorBook::factory()->count(10)->create();
    }
}
