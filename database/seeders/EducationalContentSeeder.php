<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationalContent;

class EducationalContentSeeder extends Seeder
{
    public function run()
    {
        $contents = [

            //stage 5
            [
                'title' => 'ألعاب البازل',
                'description' => '',
                'image_path' => 'images/contents/puzzle.png',
                'type' => 'all',
                 'education_stage_id' => 5
            ],
            [
                'title' => 'الأنماط الصوتية',
                'description' => '',
                'image_path' => 'images/contents/patterns.png',
                'type' => 'all',
                 'education_stage_id' => 5
            ],
            [
                'title' => 'تكوين الكلمات',
                'description' => '',
                'image_path' => 'images/contents/word.png',
                'type' => 'all',
                 'education_stage_id' => 5
            ],
            [
                'title' => 'مطابقة الأشكال',
                'description' => '',
                'image_path' => 'images/contents/image.png',
                'type' => 'all',
                 'education_stage_id' => 4
            ],
            //stage 4
            [
                'title' => 'قصص الأنبياء',
                'description' => 'اختر قصة نبي وتعلم قيم الحكمة والشجاعة ابدأ مغامرتك الاّن.',
                'image_path' => 'images/contents/prophets.png',
                'type' => 'all',
                 'education_stage_id' => 4
            ],
            [
                'title' => 'القيم الأخلاقية',
                'description' => 'تعلم القيم الأخلاقية الاسلامية الان.',
                'image_path' => 'images/contents/manners.png',
                'type' => 'all',
                 'education_stage_id' => 4
            ],
            [
                'title' => 'أعمال الصدقة',
                'description' => 'تعلم أعمال الصدقة في الاسلام الان.',
                'image_path' => 'images/contents/charity.png',
                'type' => 'all',
                 'education_stage_id' => 4
            ],
            [
                'title' => 'الأذكار والأدعية',
                'description' => 'هيا بنا نتعلم الاذكار.',
                'image_path' => 'images/contents/prayers.png',
                'type' => 'all',
                 'education_stage_id' => 4
            ],
            // stage 3
            [
                'title' => 'الحشرات',
                'description' => 'تعليم الأطفال عن الحشرات بطريقة تفاعلية وممتعة.',
                'image_path' => 'images/contents/insects.png',
                'type' => 'الكائنات الحية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الحيوانات البحرية',
                'description' => 'تعليم الأطفال عن الحيوانات البحرية وأهميتها.',
                'image_path' => 'images/contents/marine_animals.png',
                'type' => 'الكائنات الحية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الحيوانات البرية',
                'description' => 'تعليم الأطفال عن الحيوانات البرية في الغابات.',
                'image_path' => 'images/contents/wild_animals.png',
                'type' => 'الكائنات الحية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الطيور',
                'description' => 'تعليم الأطفال عن الطيور وأصواتها.',
                'image_path' => 'images/contents/birds.png',
                'type' => 'الكائنات الحية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'النباتات',
                'description' => 'تعليم الأطفال عن النباتات وكيف تنمو.',
                'image_path' => 'images/contents/plants.png',
                'type' => 'الكائنات الحية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الظواهر الفلكية',
                'description' => 'تعليم الأطفال عن الكواكب والنجوم.',
                'image_path' => 'images/contents/astronomical_phenomena.png',
                'type' => 'الظواهر الطبيعية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الظواهر الأرضية',
                'description' => 'تعليم الأطفال عن الزلازل والبراكين.',
                'image_path' => 'images/contents/earth_phenomena.png',
                'type' => 'الظواهر الطبيعية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الظواهر الجوية',
                'description' => 'تعليم الأطفال عن الطقس والسحاب.',
                'image_path' => 'images/contents/weather_phenomena.png',
                'type' => 'الظواهر الطبيعية',
                 'education_stage_id' => 3 
            ],
            [
                'title' => 'الظواهر البيئية',
                'description' => 'تعليم الأطفال عن أهمية البيئة والحفاظ عليها.',
                'image_path' => 'images/contents/environmental_phenomena.png',
                'type' => 'الظواهر الطبيعية',
                'education_stage_id' => 3 
            ],
            //stage 2
            [
                'title' => 'تعلم الحروف',
                'description' => 'هيا بنا نتعلم الحروف',
                'image_path' => 'images/contents/letter.png',
                'type' => 'all',
                'education_stage_id' => 2 
            ],
            [
                'title' => 'تركيب الكلمات',
                'description' => 'تركيب الحروف كلمات بطريقة تفاعلية ممتعة',
                'image_path' => 'images/contents/words.png',
                'type' => 'all',
                'education_stage_id' => 2 
            ],
            [
                'title' => 'قراءة القصص',
                'description' => 'هيا بنا نقرأ القصص',
                'image_path' => 'images/contents/stories.png',
                'type' => 'all',
                'education_stage_id' => 2 
            ],
            //stage 1
            [
                'title' => 'تعلم الارقام',
                'description' => 'هيا بنا نتعلم الارقام',
                'image_path' => 'images/contents/numbers.png',
                'type' => 'all',
                'education_stage_id' => 1
            ],
            [
                'title' => 'ألعاب الجمع والطرح',
                'description' => 'ألعب معنا لتتلعم الجمع والطرح',
                'image_path' => 'images/contents/plus.png',
                'type' => 'all',
                'education_stage_id' => 1
            ],
            [
                'title' => 'ترتيب الأرقام',
                'description' => 'هيا بنا نرتب الارقام بطريقة صحيحة',
                'image_path' => 'images/contents/order.png',
                'type' => 'all',
                'education_stage_id' => 1
            ],
            [
                'title' => 'الأشكال الهندسية',
                'description' => 'هيا بنا نتعلم الاشكال الهندسية',
                'image_path' => 'images/contents/Vector.png',
                'type' => 'all',
                'education_stage_id' => 1
            ],
        ];

        foreach ($contents as $content) {
            EducationalContent::create($content);
        }
    }
}
