<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CourseSeeder::class,
            QuestionSeeder::class,
            AchievementSeeder::class,
            RankSeeder::class,
        ]);
    }
}
