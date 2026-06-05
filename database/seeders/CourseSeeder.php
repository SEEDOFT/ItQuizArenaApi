<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::create([
            'id' => Course::PROGRAMMING,
            'title' => 'Programming Fundamentals',
            'description' => 'Test your knowledge of programming languages, algorithms, and data structures.',
            'category' => 'Programming',
            'difficulty' => 'Beginner',
            'question_count' => 0,
        ]);

        Course::create([
            'id' => Course::NETWORKING,
            'title' => 'Networking Basics',
            'description' => 'Explore the world of computer networks, protocols, and topologies.',
            'category' => 'Networking',
            'difficulty' => 'Beginner',
            'question_count' => 0,
        ]);

        Course::create([
            'id' => Course::DATABASE,
            'title' => 'Database Systems',
            'description' => 'Challenge yourself with SQL, NoSQL, normalization, and database design concepts.',
            'category' => 'Database',
            'difficulty' => 'Intermediate',
            'question_count' => 0,
        ]);
    }
}
