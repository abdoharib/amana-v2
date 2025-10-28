<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LettersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $arabicLetters = [
            'أ' => 'أسد', 'ب' => 'باب', 'ت' => 'تفاح', 'ث' => 'ثعلب', 'ج' => 'جمل',
            'ح' => 'حصان', 'خ' => 'خروف', 'د' => 'دجاجة', 'ذ' => 'ذئب', 'ر' => 'رمان',
            'ز' => 'زرافة', 'س' => 'سفينة', 'ش' => 'شمس', 'ص' => 'صقر', 'ض' => 'ضفدع',
            'ط' => 'طائرة', 'ظ' => 'ظبي', 'ع' => 'عنب', 'غ' => 'غزال', 'ف' => 'فراشة',
            'ق' => 'قطار', 'ك' => 'كتاب', 'ل' => 'ليمون', 'م' => 'موز', 'ن' => 'نمر',
            'ه' => 'هدهد', 'و' => 'وردة', 'ي' => 'يد'
        ];

        $data = [];

        foreach ($arabicLetters as $letter => $word) {
            $index = array_search($letter, array_keys($arabicLetters));
            $data[] = [
                'letter' => $letter,
                'word' => json_encode([
                    'text' => $word,
                    'image_path' => "images/word_$index.png",
                    'audio_path' => "audio/word_$index.mp3"
                ]),
                'image_path' => "images/letter_$index.png",
                'audio_path' => "audio/letter_$index.mp3",
                'word' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('letters')->insert($data);
    }
}
