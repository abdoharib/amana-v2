<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'number' => $i,
                'image_path' => "images/number_$i.png",
                'audio_path' => "audio/number_$i.mp3",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('numbers')->insert($data);
    }
}
