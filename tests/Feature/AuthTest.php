<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('register creates user and returns token', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'status',
            'data' => ['user', 'token'],
        ]);

    expect($response['data']['user']['name'])->toBe('Test User');
    expect($response['status']['success'])->toBeTrue();
});

test('register validates required fields', function () {
    $response = $this->postJson('/api/register', []);

    $response->assertStatus(422);
});

test('login returns token with valid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data' => ['user', 'token'],
        ]);
});

test('login fails with invalid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401);
    expect($response['status']['success'])->toBeFalse();
});

test('authenticated user can fetch their profile', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson('/api/user');

    $response->assertStatus(200);
    expect($response['data']['name'])->toBe($user->name);
});

test('unauthenticated user cannot fetch profile', function () {
    $response = $this->getJson('/api/user');

    $response->assertStatus(401);
});

test('logout revokes token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/logout');

    $response->assertStatus(200);

    expect($user->tokens()->count())->toBe(0);
});
