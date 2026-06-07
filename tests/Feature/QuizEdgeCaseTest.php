<?php

use App\Models\Achievement;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->headers = ['Authorization' => "Bearer {$this->user->createToken('test')->plainTextToken}"];
    $this->course = Course::factory()->create();
    Question::factory()->count(10)->for($this->course)->beginner()->create();
    Achievement::factory()->create(['key' => 'first_quiz', 'required_value' => 1]);
});

test('show returns session with answers', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];
    $questionId = $start['data']['questions'][0]['id'];

    $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/answer", [
            'question_id' => $questionId,
            'selected_option' => 0,
            'time_spent' => 5,
        ]);

    $response = $this->withHeaders($this->headers)
        ->getJson("/api/quiz/{$sessionId}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['session', 'answers'],
        ]);
    expect($response['data']['answers'])->toHaveCount(1);
});

test('show returns 403 for other users session', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];

    $otherUser = User::factory()->create();
    $otherToken = $otherUser->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $otherToken")
        ->actingAs($otherUser)
        ->getJson("/api/quiz/{$sessionId}");

    $response->assertStatus(403);
});

test('answer returns 400 for already answered question', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];
    $questionId = $start['data']['questions'][0]['id'];

    $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/answer", [
            'question_id' => $questionId,
            'selected_option' => 0,
            'time_spent' => 5,
        ]);

    $response = $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/answer", [
            'question_id' => $questionId,
            'selected_option' => 1,
            'time_spent' => 5,
        ]);

    $response->assertStatus(400);
});

test('answer returns 403 for other users session', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];
    $questionId = $start['data']['questions'][0]['id'];

    $otherUser = User::factory()->create();
    $otherToken = $otherUser->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $otherToken")
        ->actingAs($otherUser)
        ->postJson("/api/quiz/{$sessionId}/answer", [
            'question_id' => $questionId,
            'selected_option' => 0,
            'time_spent' => 5,
        ]);

    $response->assertStatus(403);
});

test('finish completes session and returns results', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];

    $response = $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['session', 'xp_gained', 'level_up', 'new_level', 'new_achievements'],
        ]);
    expect($response['data']['session']['status'])->toBe('completed');
});

test('finish returns 200 for already completed quiz', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];

    $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $response = $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $response->assertStatus(200);
    expect($response['data']['session']['status'])->toBe('completed');
});

test('finish returns 403 for other users session', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];

    $otherUser = User::factory()->create();
    $otherToken = $otherUser->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $otherToken")
        ->actingAs($otherUser)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $response->assertStatus(403);
});

test('finish awards xp and updates user stats', function () {
    $start = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => $this->course->id]);
    $sessionId = $start['data']['session']['id'];
    $questions = $start['data']['questions'];

    foreach ($questions as $q) {
        $this->withHeaders($this->headers)
            ->postJson("/api/quiz/{$sessionId}/answer", [
                'question_id' => $q['id'],
                'selected_option' => 0,
                'time_spent' => 5,
            ]);
    }

    $this->withHeaders($this->headers)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $this->user->refresh();
    expect($this->user->total_quizzes)->toBe(1);
});

test('finish with correct answers scores points', function () {
    $course = Course::factory()->create();
    Question::factory()->count(3)->for($course)->create(['correct_answer' => 0, 'points' => 50]);

    $headers = ['Authorization' => "Bearer {$this->user->createToken('test')->plainTextToken}"];
    $start = $this->withHeaders($headers)
        ->postJson('/api/quiz/start', ['course_id' => $course->id, 'question_count' => 3]);
    $sessionId = $start['data']['session']['id'];

    foreach ($start['data']['questions'] as $q) {
        $this->withHeaders($headers)
            ->postJson("/api/quiz/{$sessionId}/answer", [
                'question_id' => $q['id'],
                'selected_option' => 0,
                'time_spent' => 5,
            ])->assertStatus(200);
    }

    $response = $this->withHeaders($headers)
        ->postJson("/api/quiz/{$sessionId}/finish");

    $response->assertStatus(200);
    expect($response['data']['session']['status'])->toBe('completed');
    expect($this->user->fresh()->total_quizzes)->toBe(1);
    expect($this->user->fresh()->xp)->toBeGreaterThan(0);
});

test('start quiz validates non-existent course', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', ['course_id' => 99999]);

    $response->assertStatus(422);
});

test('start quiz validates question_count bounds', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/quiz/start', [
            'course_id' => $this->course->id,
            'question_count' => 0,
        ]);

    $response->assertStatus(422);
});

test('session accuracy returns 0 for no questions', function () {
    $session = QuizSession::factory()->create([
        'user_id' => $this->user->id,
        'course_id' => $this->course->id,
        'total_questions' => 0,
    ]);

    expect($session->accuracy)->toBe(0.0);
    expect($session->is_perfect)->toBeFalse();
});

test('is_perfect returns true only when all correct', function () {
    $session = QuizSession::factory()->create([
        'user_id' => $this->user->id,
        'course_id' => $this->course->id,
        'correct_count' => 10,
        'total_questions' => 10,
    ]);

    expect($session->is_perfect)->toBeTrue();
});
