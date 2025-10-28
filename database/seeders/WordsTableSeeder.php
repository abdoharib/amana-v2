<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class WordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $words = [
            'مدرسة',
            'كتاب',
            'قلم',
            'كرسي',
            'هاتف',
            'ساعة',
            'شجرة',
            'نهر',
            'طائرة',
            'سيارة',
            'باب',
            'نافذة',
            'ضوء',
            'مفتاح',
            'ماء',
            'زهرة',
            'صديق',
            'حديقة',
            'مكتبة',
            'مسجد',
            'كمبيوتر',
            'قط',
            'كلب',
            'طاولة',
            'ورقة',
            'سماء',
            'بحر',
            'مدينة',
            'رجل',
            'امرأة'
        ];

        $data = [];

        foreach ($words as $word) {
            $data[] = [
                'word' => $word,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('word_games')->insert($data);
    }
}
