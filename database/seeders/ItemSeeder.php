<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->insert([
            // الحيوانات المفترسة
            [
                'title' => 'الأسد',
                'image_path' => 'images/items/lion.png',
                'category_id' => 1,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'النمر',
                'image_path' => 'images/items/tiger.png',
                'category_id' => 1,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الفهد',
                'image_path' => 'images/items/cheetah.png',
                'category_id' => 1,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الذئب',
                'image_path' => 'images/items/wolf.png',
                'category_id' => 1,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // الحيوانات الأليفة
            [
                'title' => 'القط',
                'image_path' => 'images/items/cat.png',
                'category_id' => 2,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الكلب',
                'image_path' => 'images/items/dog.png',
                'category_id' => 2,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الأرنب',
                'image_path' => 'images/items/bunny.png',
                'category_id' => 2,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'السنجاب',
                'image_path' => 'images/items/squirel.png',
                'category_id' => 2,
                'educational_content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
