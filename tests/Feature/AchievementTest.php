<?php

use App\Models\Achievement;
use App\Models\User;

test('lists all achievements', function () {
    Achievement::factory()->count(3)->create();

    $response = $this->getJson('/api/achievements');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(3);
});

test('achievement resource has expected structure', function () {
    Achievement::factory()->create();

    $response = $this->getJson('/api/achievements');

    expect($response['data'][0])->toHaveKeys([
        'id', 'title', 'description', 'key', 'required_value', 'icon',
    ]);
});

test('authenticated user can fetch their achievement progress', function () {
    $achievement = Achievement::factory()->create(['key' => 'first_quiz', 'required_value' => 1]);
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/user/achievements');

    $response->assertStatus(200);
    expect($response['data'][0]['key'])->toBe('first_quiz');
});

test('unauthenticated user cannot fetch their achievements', function () {
    $response = $this->getJson('/api/user/achievements');
    $response->assertStatus(401);
});
