<?php

namespace App\DTOs\API;

use Illuminate\Http\JsonResponse;

readonly class ApiResponseDTO
{
    public function __construct(
        public bool $success,
        public string $message,
        public mixed $data = null,
        public mixed $errors = null,
        public ?array $meta = null,
        public int $statusCode = 200,
    ) {}

    public function toResponse(): JsonResponse
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if (! is_null($this->data)) {
            $response['data'] = $this->data;
        }

        if (! is_null($this->errors)) {
            $response['errors'] = $this->errors;
        }

        $response['meta'] = $this->meta ?? [
            'timestamp' => now()->toIso8601String(),
        ];

        return response()->json($response, $this->statusCode);
    }

    public static function success(string $message, mixed $data = null, ?array $meta = null, int $code = 200): JsonResponse
    {
        return (new self(true, $message, $data, null, $meta, $code))->toResponse();
    }

    public static function error(string $message, mixed $errors = null, int $code = 400, ?array $meta = null): JsonResponse
    {
        return (new self(false, $message, null, $errors, $meta, $code))->toResponse();
    }
}
