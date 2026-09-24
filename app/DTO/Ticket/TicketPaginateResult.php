<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

final readonly class TicketPaginateResult
{
    public function __construct(
        private array $rows,
        private int $total,
    ) {}

    /**
     * @return array
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
