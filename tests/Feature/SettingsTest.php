<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test')->plainTextToken;
    $this->headers = ['Authorization' => "Bearer {$this->token}"];
});

test('get settings returns defaults', function () {
    $response = $this->withHeaders($this->headers)
        ->getJson('/api/user/settings');

    $response->assertStatus(200);
    expect($response['data']['sound_enabled'])->toBeTrue();
    expect($response['data']['difficulty'])->toBe('Beginner');
});

test('update settings stores changes', function () {
    $response = $this->withHeaders($this->headers)
        ->putJson('/api/user/settings', [
            'sound_enabled' => false,
            'music_enabled' => false,
            'show_explanation' => true,
            'question_count' => 20,
            'time_per_question' => 45,
            'theme_mode' => 'dark',
            'difficulty' => 'Advanced',
        ]);

    $response->assertStatus(200);

    $fresh = $this->withHeaders($this->headers)
        ->getJson('/api/user/settings');

    expect($fresh['data']['sound_enabled'])->toBeFalse();
    expect($fresh['data']['question_count'])->toBe(20);
    expect($fresh['data']['difficulty'])->toBe('Advanced');
});

test('unauthenticated user cannot access settings', function () {
    $response = $this->getJson('/api/user/settings');
    $response->assertStatus(401);
});
