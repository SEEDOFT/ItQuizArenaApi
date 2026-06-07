<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('google login creates new user from valid token', function () {
    Http::fake([
        'oauth2.googleapis.com/*' => Http::response([
            'aud' => config('services.google.client_id'),
            'sub' => 'google-123',
            'email' => 'googleuser@example.com',
            'name' => 'Google User',
            'picture' => 'https://example.com/avatar.png',
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', [
        'token' => 'valid-google-token',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status', 'data' => ['user', 'token'],
        ]);

    expect($response['data']['user']['email'])->toBe('googleuser@example.com');
    expect(User::where('google_id', 'google-123')->exists())->toBeTrue();
});

test('google login links existing email user', function () {
    $user = User::factory()->create(['email' => 'existing@example.com', 'google_id' => null]);

    Http::fake([
        'oauth2.googleapis.com/*' => Http::response([
            'aud' => config('services.google.client_id'),
            'sub' => 'google-456',
            'email' => 'existing@example.com',
            'name' => 'Existing User',
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', ['token' => 'valid-token']);

    $response->assertStatus(200);
    expect($user->fresh()->google_id)->toBe('google-456');
});

test('google login returns existing user token', function () {
    $user = User::factory()->create([
        'google_id' => 'google-existing',
        'email' => 'returning@example.com',
    ]);

    Http::fake([
        'oauth2.googleapis.com/*' => Http::response([
            'aud' => config('services.google.client_id'),
            'sub' => 'google-existing',
            'email' => 'returning@example.com',
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', ['token' => 'valid-token']);

    $response->assertStatus(200);
    expect($response['data']['user']['id'])->toBe($user->id);
});

test('google login fails with invalid token', function () {
    Http::fake([
        'oauth2.googleapis.com/*' => Http::response([], 400),
    ]);

    $response = $this->postJson('/api/auth/google', ['token' => 'bad-token']);

    $response->assertStatus(401);
    expect($response['status']['success'])->toBeFalse();
});

test('google login validates required token field', function () {
    $response = $this->postJson('/api/auth/google', []);

    $response->assertStatus(422);
});

test('google login validates token is string', function () {
    $response = $this->postJson('/api/auth/google', ['token' => 123]);

    $response->assertStatus(422);
});

test('google login creates unique username when name conflicts', function () {
    User::factory()->create(['username' => 'john_doe']);

    Http::fake([
        'oauth2.googleapis.com/*' => Http::response([
            'aud' => config('services.google.client_id'),
            'sub' => 'google-unique',
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', ['token' => 'valid-token']);

    $response->assertStatus(200);
    expect($response['data']['user']['username'])->toMatch('/^john_doe(_\d+)?$/');
});

test('google login handles missing user data from provider', function () {
    Http::fake([
        'oauth2.googleapis.com/*' => Http::response([
            'aud' => config('services.google.client_id'),
            'sub' => 'google-nodata',
            'email' => 'nodata@example.com',
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', ['token' => 'valid-token']);

    $response->assertStatus(200);
    expect($response['data']['user']['name'])->toBe('nodata@example.com');
});
