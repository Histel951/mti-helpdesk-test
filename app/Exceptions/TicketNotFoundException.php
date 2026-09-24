<?php

declare(strict_types = 1);

namespace App\Exceptions;

use RuntimeException;

final class TicketNotFoundException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct(
            message: 'Ticket not found.',
        );
    }
}
