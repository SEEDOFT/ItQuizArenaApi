<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    public function success(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => [
                'code' => (string) $code,
                'message' => $message,
                'success' => true,
            ],
            'data' => $this->resolveData($data),
        ], $code);
    }

    public function created(mixed $data, string $message = 'Created successfully'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    public function error(string $message = 'Error', int $code = 400, mixed $errors = null): JsonResponse
    {
        $data = $errors !== null ? ['errors' => $errors] : [];

        return response()->json([
            'status' => [
                'code' => (string) $code,
                'message' => $message,
                'success' => false,
            ],
            'data' => $data,
        ], $code);
    }

    public function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    private function resolveData(mixed $data): mixed
    {
        if ($data instanceof ResourceCollection) {
            return $data->collection->toArray();
        }

        if ($data instanceof JsonResource) {
            return $data->resolve(request());
        }

        if (\is_array($data)) {
            return \array_map(fn ($item) => $item instanceof JsonResource ? $item->resolve(request()) : $item, $data);
        }

        return $data;
    }
}
