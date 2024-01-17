<?php

namespace Database\Seeders;

use App\Models\CommentBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommentBook::factory()->count(10)->create();
    }
}
