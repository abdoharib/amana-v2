<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(EducationStageSeeder::class);
        $this->call(EducationalContentSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(ItemDetailSeeder::class);
        $this->call(PrayerSeeder::class);
        $this->call(NumberSeeder::class);
        $this->call(LettersTableSeeder::class);
        $this->call(WordsTableSeeder::class);

    }
}
