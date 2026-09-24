<?php

declare(strict_types = 1);

namespace App\UseCases\Ticket\DTO;

use App\DTO\Ticket\TicketListItem;

final readonly class TicketListResult
{
    public function __construct(
        private int $page,
        private int $perPage,
        private array $items,
        private int $total,
    ) {}

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return array<TicketListItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
