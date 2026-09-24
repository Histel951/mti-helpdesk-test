<?php

declare(strict_types = 1);

namespace App\UseCases\Ticket\DTO;

use App\DTO\Ticket\TicketComment;
use App\Enums\TicketStatus;

final readonly class ApplyTicketEventResult
{
    public function __construct(
        private int            $id,
        private int            $version,
        private TicketStatus   $status,
        private ?TicketComment $comment,
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

    public function getComment(): ?TicketComment
    {
        return $this->comment;
    }
}
