<?php

namespace App\Repositories\Ticket;

use App\DTO\Ticket\CreateTicketData;
use App\DTO\Ticket\TicketComment;
use App\DTO\Ticket\TicketIndexQuery;
use App\DTO\Ticket\TicketPaginateResult;
use App\Enums\TicketStatus;
use App\Exceptions\TicketNotFoundException;

interface TicketRepositoryInterface
{
    /**
     * Получение тикета перед обновлением
     *
     * @param int $id
     * @return object
     * @throws TicketNotFoundException
     */
    public function findByIdForUpdate(int $id): object;

    /**
     * Обновляет статус тикета
     *
     * @param int $id
     * @param TicketStatus $status
     * @param int $version
     * @return void
     */
    public function updateStatus(int $id, TicketStatus $status, int $version): void;

    /**
     * Добавляет комментарий к тикету
     *
     * @param int $id
     * @param TicketComment $comment
     * @return void
     */
    public function addComment(int $id, TicketComment $comment): void;

    /**
     * Пагинация по фильтрам
     *
     * @param TicketIndexQuery $query
     * @return TicketPaginateResult
     */
    public function paginate(TicketIndexQuery $query): TicketPaginateResult;

    /**
     * Создаёт тикет в базе
     *
     * @param CreateTicketData $data
     * @return int
     */
    public function create(CreateTicketData $data): int;
}
