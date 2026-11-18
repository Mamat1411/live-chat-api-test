<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(string $message, int $status = 500, mixed $errors = null): JsonResponse {
        return response()->json(array_filter([
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ], fn($value) => !is_null($value)), $status);
    }
}
