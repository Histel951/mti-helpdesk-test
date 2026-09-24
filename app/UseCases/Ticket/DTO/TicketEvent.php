<?php

declare(strict_types = 1);

namespace App\UseCases\Ticket\DTO;

use App\Enums\TicketStatus;

final readonly class TicketEvent
{
    public function __construct(
        private int $id,
        private int $version,
        private ?TicketStatus $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            version: $data['version'],
            status: isset($data['status'])
                ? TicketStatus::from($data['status'])
                : null,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getStatus(): ?TicketStatus
    {
        return $this->status;
    }
}
