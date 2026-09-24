<?php

declare(strict_types = 1);

namespace App\UseCases\Ticket;

use App\DTO\Ticket\TicketIndexQuery;
use App\Mappers\Ticket\TicketMapper;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\UseCases\Ticket\DTO\TicketListResult;

final readonly class ListTicketsUseCase
{
    public function __construct(
        private TicketRepositoryInterface $repository,
        private TicketMapper $mapper,
    ) {}

    public function execute(
        TicketIndexQuery $query,
    ): TicketListResult {
        $paginate = $this->repository->paginate($query);

        $items = $this->mapper->rowsToListItems(
            $paginate->getRows()
        );

        return new TicketListResult(
            page: $query->getPage(),
            perPage: $query->getPerPage(),
            items: $items,
            total: $paginate->getTotal(),
        );
    }
}
