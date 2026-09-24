<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

final readonly class TicketPaginateResult
{
    /**
     * @param array<int, object> $rows
     */
    public function __construct(
        private array $rows,
        private int $total,
    ) {}

    /**
     * @return array<int, object>
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
