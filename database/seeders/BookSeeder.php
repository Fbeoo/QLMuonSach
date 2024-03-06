<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::where('category_parent_id','<>',null)->get();
        foreach ($category as $item) {
            Book::factory()->count(2)->sequence(
                [
                    'category_id' => $item->id
                ]
            )->create();
        }
    }
}
