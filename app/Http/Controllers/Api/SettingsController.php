<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Resources\SettingsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return $this->success(new SettingsResource($request->user()->settings));
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $settings = $request->user()->settings;
        $settings->update($request->validated());

        return $this->success(new SettingsResource($settings->fresh()), 'Settings updated successfully');
    }
}
