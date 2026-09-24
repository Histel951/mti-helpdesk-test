<?php

declare(strict_types = 1);

namespace App\DTO;

use App\Enums\ApiErrorCode;
use Illuminate\Http\JsonResponse;

final readonly class ApiError
{
    /**
     * @param array<string, mixed>|null $details
     */
    public function __construct(
        private ApiErrorCode $code,
        private string $message,
        private int $status,
        private ?array $details = null,
    ) {}

    public function toResponse(): JsonResponse
    {
        $error = [
            'code' => $this->code->value,
            'message' => $this->message,
        ];

        if ($this->details !== null) {
            $error['details'] = $this->details;
        }

        return response()->json([
            'error' => $error,
        ], $this->status);
    }
}
