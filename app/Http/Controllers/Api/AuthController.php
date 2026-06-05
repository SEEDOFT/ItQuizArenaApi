<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('quiz-app-token')->plainTextToken;

        return $this->created([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'User registered successfully');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('quiz-app-token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user->load('settings')),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * OAuth Login with Google Account ( OAuth )
     */
    public function googleLogin(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'token' => ['required', 'string', 'max:2048'],
        ]);

        try {
            $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $validatedData['token'],
            ])->throw();

            $googleUser = $response->json();

            $allowedAudiences = array_filter([
                config('services.google.client_id'),
                config('services.google.ios_client_id'),
            ]);

            if (! in_array($googleUser['aud'] ?? '', $allowedAudiences)) {
                return $this->error('Invalid Google token', 401);
            }
        } catch (Exception $e) {
            return $this->error('Invalid Google token', 401);
        }

        if (! isset($googleUser['email'], $googleUser['sub'])) {
            return $this->error('Invalid Google user data', 401);
        }

        $user = User::where('google_id', $googleUser['sub'])->first();

        if (! $user) {
            $user = User::where('email', $googleUser['email'])->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser['sub'],
                    'avatar' => $googleUser['picture'] ?? null,
                ]);
            } else {
                $baseUsername = Str::slug(
                    $googleUser['name'] ?? explode('@', $googleUser['email'])[0],
                    '_'
                );
                $username = $baseUsername;
                $counter = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername.'_'.$counter;
                    $counter++;
                }

                $user = User::create([
                    'name' => $googleUser['name'] ?? $googleUser['email'],
                    'username' => $username,
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['sub'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'password' => null,
                ]);
            }
        }

        $user->tokens()->delete();
        $token = $user->createToken('quiz-app-token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user->load('settings')),
            'token' => $token,
        ], 'Google login successful');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success([], 'Logged out successfully');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->success(new UserResource($request->user()->load('settings')));
    }
}
