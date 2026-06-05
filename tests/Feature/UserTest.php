<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'xp' => 100,
        'level' => 1,
        'total_quizzes' => 5,
    ]);
    $this->token = $this->user->createToken('test')->plainTextToken;
    $this->headers = ['Authorization' => "Bearer {$this->token}"];
});

test('get user stats returns data', function () {
    $response = $this->withHeaders($this->headers)
        ->getJson('/api/user/stats');

    $response->assertStatus(200);
    expect($response['data']['total_quizzes'])->toBe(5);
    expect($response['data']['xp'])->toBe(100);
});

test('reset progress clears quiz data and stats', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/user/reset-progress');

    $response->assertStatus(200);

    $this->user->refresh();
    expect($this->user->xp)->toBe(0);
    expect($this->user->level)->toBe(1);
    expect($this->user->total_quizzes)->toBe(0);
    expect($this->user->highest_score)->toBe(0);
    expect($this->user->best_streak)->toBe(0);
});

test('unauthenticated user cannot access stats', function () {
    $response = $this->getJson('/api/user/stats');
    $response->assertStatus(401);
});

test('unauthenticated user cannot reset progress', function () {
    $response = $this->postJson('/api/user/reset-progress');
    $response->assertStatus(401);
});
