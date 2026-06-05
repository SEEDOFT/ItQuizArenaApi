<?php

use App\Models\Course;

test('lists all active courses', function () {
    Course::factory()->count(3)->create();

    $response = $this->getJson('/api/courses');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(3);
});

test('returns empty list when no courses exist', function () {
    $response = $this->getJson('/api/courses');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(0);
});

test('course resource has expected structure', function () {
    $course = Course::factory()->create();

    $response = $this->getJson('/api/courses');

    $response->assertStatus(200);
    expect($response['data'][0])->toHaveKeys([
        'id', 'title', 'description', 'category', 'difficulty',
        'question_count', 'thumbnail', 'created_at',
    ]);
});
