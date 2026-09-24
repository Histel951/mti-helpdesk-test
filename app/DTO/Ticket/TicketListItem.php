<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

use App\Enums\TicketStatus;
use DateTimeImmutable;

final readonly class TicketListItem
{
    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private TicketStatus $status,
        private int $version,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): TicketStatus
    {
        return $this->status;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
