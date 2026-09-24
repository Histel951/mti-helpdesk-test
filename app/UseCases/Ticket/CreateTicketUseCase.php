<?php

declare(strict_types = 1);

namespace App\UseCases\Ticket;

use App\DTO\Ticket\CreateTicketData;
use App\Repositories\Ticket\TicketRepositoryInterface;

final readonly class CreateTicketUseCase
{
    public function __construct(
        private TicketRepositoryInterface $repository,
    ) {}

    /**
     * Возвращает id нового тикета
     */
    public function execute(CreateTicketData $data): int
    {
        return $this->repository->create($data);
    }
}
