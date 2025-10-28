<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('education_stages')->insert([
            [
                'title' => 'الرياضيات الأساسية',
                'description' => 'تعليم الأرقام, الجمع,الطرح, وترتيب الأشكال الهندسية',
                'image_path' => 'images/stages/math.png',

            ],
            [
                'title' => 'اللغة والتواصل',
                'description' => 'تعليم الحروف , الكلمات والنطق عبر أنشطة تفاعلية ممتعة',
                'image_path' => 'images/stages/language.png',
            ],
            [
                'title' => 'العلم والاكتشاف',
                'description' => 'تعليم الأطفال عن الكائنات الحية والظواهر الطبيعية بظريقة تفاعلية وممتعة',
                'image_path' => 'images/stages/science.png',
            ],
            [
                'title' => 'التربية الإسلامية والقيم الأخلاقية',
                'description' => 'قصص الانبياء, الأذكار التفاعلية وألعاب القيم الأخلاقية لتعزيز التعلم الديني',
                'image_path' => 'images/stages/islamic.png',
            ],
            [
                'title' => 'التربية الفنية والإبداعية',
                'description' => 'تعزيز الإبداع والمهارات الفنية لدى الإطفال من خلال أنشطة',
                'image_path' => 'images/stages/creative.png',
            ],
        ]);
    }
}
