<?php

use App\Models\Achievement;
use App\Models\Course;
use App\Models\QuizSession;
use App\Models\User;
use App\Services\AchievementService;

test('checkUnlocks returns empty when no achievements', function () {
    $user = User::factory()->create();
    $session = QuizSession::factory()->create(['user_id' => $user->id]);

    $service = app(AchievementService::class);
    $result = $service->checkUnlocks($user, $session);

    expect($result)->toBe([]);
});

test('checkUnlocks returns newly unlocked achievement key', function () {
    Achievement::factory()->create(['key' => 'first_quiz', 'required_value' => 1]);
    $user = User::factory()->create(['total_quizzes' => 1]);
    $session = QuizSession::factory()->create(['user_id' => $user->id]);

    $service = app(AchievementService::class);
    $result = $service->checkUnlocks($user, $session);

    expect($result)->toContain('first_quiz');
});

test('checkUnlocks skips already unlocked achievements', function () {
    $achievement = Achievement::factory()->create(['key' => 'first_quiz', 'required_value' => 1]);
    $user = User::factory()->create();
    $session = QuizSession::factory()->create(['user_id' => $user->id, 'course_id' => Course::factory()]);

    $ua = $user->userAchievements()->where('achievement_id', $achievement->id)->first();
    $ua->update(['progress' => 1, 'unlocked_at' => now()]);

    $service = app(AchievementService::class);
    $result = $service->checkUnlocks($user, $session);

    expect($result)->toBe([]);
});

test('checkUnlocks does not unlock when progress insufficient', function () {
    Achievement::factory()->create(['key' => 'ten_quizzes', 'required_value' => 10]);
    $user = User::factory()->create(['total_quizzes' => 3]);
    $session = QuizSession::factory()->create(['user_id' => $user->id]);

    $service = app(AchievementService::class);
    $result = $service->checkUnlocks($user, $session);

    expect($result)->toBe([]);
});
