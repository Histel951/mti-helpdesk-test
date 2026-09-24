<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\DTO\ApiError;
use App\Enums\ApiErrorCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey || !hash_equals(
                (string) config('app.api_key'),
                $apiKey
            )) {
            $error = new ApiError(
                code: ApiErrorCode::UNAUTHORIZED,
                message: 'Invalid API key.',
                status: 401,
            );

            return $error->toResponse();
        }

        return $next($request);
    }
}
