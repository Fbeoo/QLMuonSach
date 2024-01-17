<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CommentBook;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
            AuthorInfoSeeder::class,
            AuthorBookSeeder::class,
            UserSeeder::class,
            HistoryRentBookSeeder::class,
            DetailHistoryRentBookSeeder::class,
            AdminSeeder::class,
            CommentBookSeeder::class
        ]);
    }
}
