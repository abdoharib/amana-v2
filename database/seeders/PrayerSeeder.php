<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('prayers')->insert([
            [
                'id' => 1,
                'title' => 'أذكار المساء',
                'image_path' => 'images/prayers/night.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'أذكار الصباح',
                'image_path' => 'images/prayers/vector.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'title' => 'أذكار بعد الصلاة',
                'image_path' => 'images/prayers/prayer.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'title' => 'أذكار قبل النوم',
                'image_path' => 'images/prayers/slep.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'title' => 'أذكار الطعام',
                'image_path' => 'images/prayers/food.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'title' => 'أذكار الاستيقاظ',
                'image_path' => 'images/prayers/wake.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'title' => 'أذكار الدعاء',
                'image_path' => 'images/prayers/pp.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'title' => 'أذكار السفر',
                'image_path' => 'images/prayers/travel.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
