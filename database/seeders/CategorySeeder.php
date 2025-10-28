<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'title' => 'الحيوانات المفترسة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'الحيوانات الأليفة',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
