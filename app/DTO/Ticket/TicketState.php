<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

use App\Enums\TicketStatus;

final readonly class TicketState
{
    public function __construct(
        private int $id,
        private int $version,
        private TicketStatus $status
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getStatus(): TicketStatus
    {
        return $this->status;
    }
}
