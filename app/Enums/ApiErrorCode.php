<?php

declare(strict_types = 1);

namespace App\Enums;

enum ApiErrorCode: string
{
    case UNAUTHORIZED = 'unauthorized';
    case NOT_FOUND = 'not_found';
    case VALIDATION = 'validation_error';
    case VERSION_CONFLICT = 'version_conflict';
    case INTERNAL_SERVER_ERROR = 'internal_server_error';
}
