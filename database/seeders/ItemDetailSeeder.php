<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemDetailSeeder extends Seeder
{
    public function run()
    {
        DB::table('item_details')->insert([
            [
                'title' => 'الأسد',
                'description' => 'الأسد حيوان مفترس يعيش في السافانا، يتمتع بقوة كبيرة.',
                'audio_path' => 'path_to_audio/الأسد_1.mp3',
                'image_path' => 'images/item-details/1.png',
                'item_id' => 1, // Assuming the lion's ID in the `items` table is 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الأسد',
                'description' => 'الأسد يعيش في أفريقيا والمناطق الجافة.',
                'audio_path' => 'path_to_audio/الأسد_2.mp3',
                'image_path' => 'images/item-details/2.png',
                'item_id' => 1, // Lion's ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'الأسد',
                'description' => 'الأسد يتغذى على الغزلان، الزرافات، والحيوانات البرية.',
                'audio_path' => 'path_to_audio/الأسد_3.mp3',
                'image_path' => 'images/item-details/3.png',
                'item_id' => 1, // Lion's ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
