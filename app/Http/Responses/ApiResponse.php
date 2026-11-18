<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(string $message, int $status = 500, mixed $errors = null): JsonResponse
    {
        return response()->json(array_filter([
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ], fn($value) => !is_null($value)), $status);
    }

    public static function paginated(ResourceCollection $collection, string $message = 'Success'): JsonResponse
    {
        $page = $collection->response()->getData(true);

        return response()->json(array_filter([
            'status' => 200,
            'message' => $message,
            'data' => $page['data'],
            'links' => $page['links'] ?? null,
            'meta' => $page['meta'] ?? null,
        ], fn($value) => !is_null($value)));
    }
}
