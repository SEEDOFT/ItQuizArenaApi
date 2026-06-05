<?php

use App\Models\Rank;

test('lists all ranks ordered by required_xp', function () {
    Rank::factory()->create(['title' => 'Beginner', 'required_xp' => 0]);
    Rank::factory()->create(['title' => 'Expert', 'required_xp' => 1000]);
    Rank::factory()->create(['title' => 'Intermediate', 'required_xp' => 500]);

    $response = $this->getJson('/api/ranks');

    $response->assertStatus(200);
    expect($response['data'][0]['required_xp'])->toBe(0);
    expect($response['data'][1]['required_xp'])->toBe(500);
    expect($response['data'][2]['required_xp'])->toBe(1000);
});

test('rank resource has expected structure', function () {
    Rank::factory()->create();

    $response = $this->getJson('/api/ranks');

    expect($response['data'][0])->toHaveKeys(['id', 'title', 'required_xp', 'icon']);
});

test('returns empty when no ranks exist', function () {
    $response = $this->getJson('/api/ranks');

    $response->assertStatus(200);
    expect($response['data'])->toHaveCount(0);
});
