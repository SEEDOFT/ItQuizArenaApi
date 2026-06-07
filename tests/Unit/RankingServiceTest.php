<?php

use App\Models\Rank;
use App\Models\User;
use App\Services\RankingService;

test('getCurrentRank returns null when no ranks exist', function () {
    $user = User::factory()->create(['xp' => 0]);

    $service = app(RankingService::class);
    $rank = $service->getCurrentRank($user);

    expect($rank)->toBeNull();
});

test('getCurrentRank returns correct rank for xp', function () {
    Rank::factory()->create(['title' => 'Beginner', 'required_xp' => 0]);
    Rank::factory()->create(['title' => 'Intermediate', 'required_xp' => 500]);
    Rank::factory()->create(['title' => 'Expert', 'required_xp' => 1000]);

    $user = User::factory()->create(['xp' => 600]);

    $service = app(RankingService::class);
    $rank = $service->getCurrentRank($user);

    expect($rank->title)->toBe('Intermediate');
});

test('getCurrentRank returns highest rank when xp exceeds all', function () {
    Rank::factory()->create(['title' => 'Beginner', 'required_xp' => 0]);
    Rank::factory()->create(['title' => 'Expert', 'required_xp' => 1000]);

    $user = User::factory()->create(['xp' => 5000]);

    $service = app(RankingService::class);
    $rank = $service->getCurrentRank($user);

    expect($rank->title)->toBe('Expert');
});

test('getNextRank returns null when user exceeds all ranks', function () {
    Rank::factory()->create(['title' => 'Beginner', 'required_xp' => 0]);
    Rank::factory()->create(['title' => 'Expert', 'required_xp' => 1000]);

    $user = User::factory()->create(['xp' => 5000]);

    $service = app(RankingService::class);
    $nextRank = $service->getNextRank($user);

    expect($nextRank)->toBeNull();
});

test('getNextRank returns next rank in sequence', function () {
    Rank::factory()->create(['title' => 'Beginner', 'required_xp' => 0]);
    Rank::factory()->create(['title' => 'Intermediate', 'required_xp' => 500]);
    Rank::factory()->create(['title' => 'Expert', 'required_xp' => 1000]);

    $user = User::factory()->create(['xp' => 200]);

    $service = app(RankingService::class);
    $nextRank = $service->getNextRank($user);

    expect($nextRank->title)->toBe('Intermediate');
});

test('getLeaderboard returns top users by xp', function () {
    User::factory()->create(['xp' => 100]);
    User::factory()->create(['xp' => 900]);
    User::factory()->create(['xp' => 500]);

    $service = app(RankingService::class);
    $leaderboard = $service->getLeaderboard(2);

    expect($leaderboard)->toHaveCount(2);
    expect($leaderboard->first()->xp)->toBe(900);
});

test('getLeaderboard respects limit', function () {
    User::factory()->count(10)->create();

    $service = app(RankingService::class);
    $leaderboard = $service->getLeaderboard(3);

    expect($leaderboard)->toHaveCount(3);
});

test('getLeaderboard returns empty when no users', function () {
    $service = app(RankingService::class);
    $leaderboard = $service->getLeaderboard();

    expect($leaderboard)->toHaveCount(0);
});
