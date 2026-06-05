<?php

use App\Models\User;

test('leaderboard returns empty when no users', function () {
    $response = $this->getJson('/api/leaderboard');

    $response->assertStatus(200);
    expect($response['data'])->toBeArray();
});

test('leaderboard returns users ordered by xp', function () {
    $low = User::factory()->create(['xp' => 100, 'level' => 1]);
    $high = User::factory()->create(['xp' => 500, 'level' => 3]);
    $mid = User::factory()->create(['xp' => 300, 'level' => 2]);

    $response = $this->getJson('/api/leaderboard');

    $response->assertStatus(200);
    expect($response['data'][0]['xp'])->toBe(500);
    expect($response['data'][1]['xp'])->toBe(300);
    expect($response['data'][2]['xp'])->toBe(100);
});

test('leaderboard respects limit parameter', function () {
    User::factory()->count(5)->create();

    $response = $this->getJson('/api/leaderboard?limit=3');

    expect($response['data'])->toHaveCount(3);
});

test('leaderboard entry has expected structure', function () {
    User::factory()->create(['xp' => 200, 'level' => 2, 'name' => 'Test Player']);

    $response = $this->getJson('/api/leaderboard');

    expect($response['data'][0])->toHaveKeys([
        'player_name', 'xp', 'level', 'rank', 'total_quizzes', 'avatar',
    ]);
});
