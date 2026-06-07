<?php

use App\Models\Course;
use App\Models\Question;
use App\Models\User;

test('show returns active course', function () {
    $course = Course::factory()->create(['is_active' => true]);

    $response = $this->getJson("/api/courses/{$course->id}");

    $response->assertStatus(200);
    expect($response['data']['id'])->toBe($course->id);
});

test('show returns 404 for inactive course', function () {
    $course = Course::factory()->create(['is_active' => false]);

    $response = $this->getJson("/api/courses/{$course->id}");

    $response->assertStatus(404);
});

test('show returns 404 for non-existent course', function () {
    $response = $this->getJson('/api/courses/99999');

    $response->assertStatus(404);
});

test('show resource has expected structure', function () {
    $course = Course::factory()->create();

    $response = $this->getJson("/api/courses/{$course->id}");

    $response->assertStatus(200);
    expect($response['data'])->toHaveKeys([
        'id', 'title', 'description', 'category', 'difficulty',
        'question_count', 'thumbnail', 'created_at',
    ]);
});

test('questions endpoint returns course questions', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $course = Course::factory()->create(['is_active' => true]);
    Question::factory()->count(5)->for($course)->create();

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/api/courses/{$course->id}/questions");

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(5);
});

test('questions endpoint returns empty for course with no questions', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $course = Course::factory()->create(['is_active' => true]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/api/courses/{$course->id}/questions");

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(0);
});

test('questions endpoint returns 404 for inactive course', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $course = Course::factory()->create(['is_active' => false]);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/api/courses/{$course->id}/questions");

    $response->assertStatus(404);
});

test('questions endpoint requires authentication', function () {
    $course = Course::factory()->create();

    $response = $this->getJson("/api/courses/{$course->id}/questions");

    $response->assertStatus(401);
});

test('question resource has expected structure', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    $course = Course::factory()->create();
    Question::factory()->for($course)->create();

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson("/api/courses/{$course->id}/questions");

    expect($response['data'][0])->toHaveKeys([
        'id', 'question_text', 'options', 'difficulty', 'points',
    ]);
});

test('filters courses by category', function () {
    Course::factory()->create(['category' => 'Programming', 'is_active' => true]);
    Course::factory()->create(['category' => 'Networking', 'is_active' => true]);

    $response = $this->getJson('/api/courses?category=Programming');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(1);
});

test('searches courses by title', function () {
    Course::factory()->create(['title' => 'PHP Basics', 'is_active' => true]);
    Course::factory()->create(['title' => 'Python Advanced', 'is_active' => true]);

    $response = $this->getJson('/api/courses?search=PHP');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(1);
    expect($response['data'][0]['title'])->toContain('PHP');
});

test('excludes inactive courses from listing', function () {
    Course::factory()->create(['is_active' => true]);
    Course::factory()->create(['is_active' => false]);

    $response = $this->getJson('/api/courses');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(1);
});
