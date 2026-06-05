<?php

use App\Models\Achievement;
use App\Models\Course;
use App\Models\Question;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test')->plainTextToken;
    $this->headers = ['Authorization' => "Bearer {$this->token}"];

    $this->course = Course::factory()->create();
    Question::factory()->count(10)->for($this->course)->beginner()->create();
    Question::factory()->count(10)->for($this->course)->intermediate()->create();
    Question::factory()->count(10)->for($this->course)->advanced()->create();

    Achievement::factory()->create(['key' => 'first_quiz', 'required_value' => 1]);
    Achievement::factory()->create(['key' => 'ten_quizzes', 'required_value' => 10]);
});

test('start quiz returns session and questions', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', [
            'course_id' => $this->course->id,
            'question_count' => 5,
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'session' => ['id', 'course', 'total_questions', 'status'],
                'questions',
            ],
        ]);

    expect($response['data']['questions'])->toHaveCount(5);
});

test('start quiz respects difficulty filter', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', [
            'course_id' => $this->course->id,
            'question_count' => 5,
            'difficulty' => 'Advanced',
        ]);

    $response->assertStatus(200);
    foreach ($response['data']['questions'] as $q) {
        expect($q['difficulty'])->toBe('Advanced');
    }
});

test('start quiz falls back to all difficulties when no match', function () {
    Question::where('course_id', $this->course->id)->delete();
    Question::factory()->count(5)->for($this->course)->create(['difficulty' => 'Beginner']);

    $response = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', [
            'course_id' => $this->course->id,
            'question_count' => 3,
            'difficulty' => 'Advanced',
        ]);

    $response->assertStatus(200);
    expect($response['data']['questions'])->toHaveCount(3);
});

test('start quiz validates required fields', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', []);

    $response->assertStatus(422);
});

test('answer question returns result', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);

    $sessionId = $start['data']['session']['id'];
    $questionId = $start['data']['questions'][0]['id'];

    $response = $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/answer", [
            'question_id' => $questionId,
            'selected_option' => 0,
            'time_spent' => 10,
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['is_correct', 'points_earned', 'session', 'is_last_question'],
        ]);
});

test('finish quiz returns results', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);

    $sessionId = $start['data']['session']['id'];

    $response = $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['session', 'xp_gained', 'level_up', 'new_level', 'new_achievements'],
        ]);
});

test('unauthenticated user cannot access quiz', function () {
    $response = $this->postJson('/api/quiz/start', ['course_id' => 1]);
    $response->assertStatus(401);
});
