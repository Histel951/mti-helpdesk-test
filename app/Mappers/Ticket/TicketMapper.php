<?php

declare(strict_types = 1);

namespace App\Mappers\Ticket;

use App\DTO\Ticket\TicketState;
use App\DTO\Ticket\TicketListItem;
use App\Enums\TicketStatus;
use DateMalformedStringException;
use DateTimeImmutable;

class TicketMapper
{
    /**
     * Преобразование строки БД в объект состояния тикета
     *
     * @param object $row
     * @return TicketState
     */
    public function toState(object $row): TicketState
    {
        return new TicketState(
            id: (int) $row->id,
            version: (int) $row->version,
            status: TicketStatus::from($row->status)
        );
    }

    /**
     * Преобразование строк из БД в array<TicketListItem>
     *
     * @param array<int, object> $rows
     * @return array<int, TicketListItem>
     * @throws DateMalformedStringException
     */
    public function rowsToListItems(array $rows): array
    {
        return array_map(
            fn (object $row) => $this->toListItem($row),
            $rows,
        );
    }

    /**
     * Преобразование строки из БД в TicketListItem
     *
     * @param object $row
     * @return TicketListItem
     * @throws DateMalformedStringException
     */
    public function toListItem(object $row): TicketListItem
    {
        return new TicketListItem(
            id: (int) $row->id,
            title: $row->title,
            description: $row->description,
            status: TicketStatus::from($row->status),
            version: (int) $row->version,
            createdAt: new DateTimeImmutable($row->created_at),
            updatedAt: new DateTimeImmutable($row->updated_at),
        );
    }
}
