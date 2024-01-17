<?php

namespace Database\Seeders;

use App\Models\AuthorInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AuthorInfo::factory()->count(10)->create();
    }
}
