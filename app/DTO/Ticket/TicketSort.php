<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

use InvalidArgumentException;

final readonly class TicketSort
{
    private function __construct(
        private string $column,
        private string $direction,
    ) {}

    public static function fromString(string $sort): self
    {
        return match ($sort) {
            'created_at' => new self('created_at', 'ASC'),
            '-created_at' => new self('created_at', 'DESC'),
            'updated_at' => new self('updated_at', 'ASC'),
            '-updated_at' => new self('updated_at', 'DESC'),
            default => throw new InvalidArgumentException(
                "Unsupported sort: {$sort}",
            ),
        };
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
