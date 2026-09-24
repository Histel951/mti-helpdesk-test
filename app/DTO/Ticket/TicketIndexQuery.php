<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

use App\Enums\TicketStatus;

final readonly class TicketIndexQuery
{
    public function __construct(
        private ?TicketStatus $status,
        private ?string $search,
        private TicketSort $sort,
        private int $perPage,
        private int $page,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: isset($data['status'])
                ? TicketStatus::from($data['status'])
                : null,
            search: $data['q'] ?? null,
            sort: TicketSort::fromString(
                $data['sort'] ?? '-created_at',
            ),
            perPage: (int) ($data['per_page'] ?? 20),
            page: (int) ($data['page'] ?? 1),
        );
    }

    public function getStatus(): ?TicketStatus
    {
        return $this->status;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSort(): TicketSort
    {
        return $this->sort;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
