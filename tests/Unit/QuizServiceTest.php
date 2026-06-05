<?php

use App\Models\Course;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\User;
use App\Services\QuizService;

test('selectQuestions returns requested number of questions', function () {
    $course = Course::factory()->create();
    Question::factory()->count(20)->create(['course_id' => $course->id]);

    $service = app(QuizService::class);
    $questions = $service->selectQuestions($course, 5);

    expect($questions)->toHaveCount(5);
});

test('selectQuestions returns limited to available count', function () {
    $course = Course::factory()->create();
    Question::factory()->count(3)->create(['course_id' => $course->id]);

    $service = app(QuizService::class);
    $questions = $service->selectQuestions($course, 10);

    expect($questions)->toHaveCount(3);
});

test('selectQuestions filters by difficulty', function () {
    $course = Course::factory()->create();
    Question::factory()->count(5)->create(['course_id' => $course->id, 'difficulty' => 'Beginner']);
    Question::factory()->count(5)->create(['course_id' => $course->id, 'difficulty' => 'Advanced']);

    $service = app(QuizService::class);
    $questions = $service->selectQuestions($course, 10, 'Advanced');

    expect($questions)->toHaveCount(5);
    foreach ($questions as $q) {
        expect($q->difficulty)->toBe('Advanced');
    }
});

test('selectQuestions falls back to all when difficulty has no matches', function () {
    $course = Course::factory()->create();
    Question::factory()->count(5)->create(['course_id' => $course->id, 'difficulty' => 'Beginner']);

    $service = app(QuizService::class);
    $questions = $service->selectQuestions($course, 3, 'Advanced');

    expect($questions)->toHaveCount(3);
});

test('selectQuestions returns empty when no questions exist', function () {
    $course = Course::factory()->create();

    $service = app(QuizService::class);
    $questions = $service->selectQuestions($course, 5);

    expect($questions)->toHaveCount(0);
});

test('gradeAnswer returns correct when option matches', function () {
    $course = Course::factory()->create();
    $question = Question::factory()->create(['course_id' => $course->id, 'correct_answer' => 2, 'points' => 50]);

    $service = app(QuizService::class);
    $result = $service->gradeAnswer($question, 2);

    expect($result['is_correct'])->toBeTrue();
    expect($result['points_earned'])->toBe(50);
});

test('gradeAnswer returns incorrect when option does not match', function () {
    $course = Course::factory()->create();
    $question = Question::factory()->create(['course_id' => $course->id, 'correct_answer' => 2]);

    $service = app(QuizService::class);
    $result = $service->gradeAnswer($question, 1);

    expect($result['is_correct'])->toBeFalse();
    expect($result['points_earned'])->toBe(0);
});

test('updateSession increments correct count on correct answer', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $session = QuizSession::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'correct_count' => 0,
        'wrong_count' => 0,
        'score' => 0,
        'streak' => 0,
    ]);

    $service = app(QuizService::class);
    $service->updateSession($session, true, 50, 10);

    expect($session->fresh()->correct_count)->toBe(1);
    expect($session->fresh()->score)->toBe(50);
    expect($session->fresh()->streak)->toBe(1);
});

test('updateSession resets streak on wrong answer', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $session = QuizSession::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'correct_count' => 5,
        'wrong_count' => 0,
        'score' => 100,
        'streak' => 3,
    ]);

    $service = app(QuizService::class);
    $service->updateSession($session, false, 0, 10);

    expect($session->fresh()->wrong_count)->toBe(1);
    expect($session->fresh()->streak)->toBe(0);
});

test('endSession completes and awards xp', function () {
    $user = User::factory()->create(['xp' => 0, 'total_quizzes' => 0]);
    $course = Course::factory()->create();
    $session = QuizSession::factory()->create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'score' => 100,
        'status' => 'in_progress',
    ]);

    $service = app(QuizService::class);
    $result = $service->endSession($session);

    expect($result->status)->toBe('completed');
    $user->refresh();
    expect($user->xp)->toBe(100);
    expect($user->total_quizzes)->toBe(1);
});
