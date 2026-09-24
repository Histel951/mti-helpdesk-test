<?php

use App\DTO\ApiError;
use App\Enums\ApiErrorCode;
use App\Exceptions\TicketNotFoundException;
use App\Exceptions\TicketVersionConflictException;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.key' => ApiKeyMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (
            TicketVersionConflictException $exception
        ) {
            $error = new ApiError(
                code: ApiErrorCode::VERSION_CONFLICT,
                message: 'Ticket version is outdated.',
                status: 409,
            );

            return $error->toResponse();
        });

        $exceptions->render(function (
            TicketNotFoundException $exception
        ) {
            $error = new ApiError(
                code: ApiErrorCode::NOT_FOUND,
                message: $exception->getMessage(),
                status: 404,
            );

            return $error->toResponse();
        });

        $exceptions->render(function (
            NotFoundHttpException $exception
        ) {
            $previous = $exception->getPrevious();

            if (
                $previous instanceof ModelNotFoundException
                && $previous->getModel() === Ticket::class
            ) {
                $error = new ApiError(
                    code: ApiErrorCode::NOT_FOUND,
                    message: 'Ticket not found.',
                    status: 404,
                );

                return $error->toResponse();
            }

            $error = new ApiError(
                code: ApiErrorCode::NOT_FOUND,
                message: 'Resource not found.',
                status: 404,
            );

            return $error->toResponse();
        });

        $exceptions->render(function (
            ValidationException $exception
        ) {
            $error = new ApiError(
                code: ApiErrorCode::VALIDATION,
                message: 'The given data was invalid.',
                status: 422,
                details: $exception->errors(),
            );

            return $error->toResponse();
        });

        $exceptions->render(function (
            Throwable $exception,
            Request $request
        ) {
            if (!$request->is('api/*')) {
                return null;
            }

            $error = new ApiError(
                code: ApiErrorCode::INTERNAL_SERVER_ERROR,
                message: 'Internal server error.',
                status: 500,
            );

            return $error->toResponse();
        });
    })->create();
