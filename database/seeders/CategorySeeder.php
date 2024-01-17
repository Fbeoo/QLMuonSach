<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->count(8)->sequence(
            [
                'category_name' => 'Sách nước ngoài',
                'category_parent_id' => null
            ],
            [
                'category_name' => 'Sách trong nước',
                'category_parent_id' => null
            ],
            [
                'category_name' => 'Văn học',
                'category_parent_id' => 1
            ],
            [
                'category_name' => 'Trinh thám',
                'category_parent_id' => 1
            ],
            [
                'category_name' => 'Truyện tranh',
                'category_parent_id' => 1
            ],
            [
                'category_name' => 'Văn học',
                'category_parent_id' => 2
            ],
            [
                'category_name' => 'Trinh thám',
                'category_parent_id' => 2
            ],
            [
                'category_name' => 'Truyện tranh',
                'category_parent_id' => 2
            ]
        )->create();
    }
}
