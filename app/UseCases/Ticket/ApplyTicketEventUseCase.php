<?php

declare(strict_types = 1);

namespace App\UseCases\Ticket;

use App\DTO\Ticket\TicketComment;
use App\Exceptions\TicketVersionConflictException;
use App\Mappers\Ticket\TicketMapper;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\UseCases\Ticket\DTO\ApplyTicketEventResult;
use App\UseCases\Ticket\DTO\TicketEvent;
use Illuminate\Support\Facades\DB;

final readonly class ApplyTicketEventUseCase
{
    public function __construct(
        private TicketRepositoryInterface $repository,
        private TicketMapper $mapper,
    ) {}

    public function execute(
        TicketEvent    $event,
        ?TicketComment $comment = null,
    ): ApplyTicketEventResult {
        return DB::transaction(function () use ($event, $comment) {
            $state = $this->mapper->toState(
                $this->repository->findByIdForUpdate($event->getId())
            );

            if ($state->getVersion() !== $event->getVersion()) {
                throw new TicketVersionConflictException();
            }

            $version = $state->getVersion();
            $status = $state->getStatus();

            if (
                $event->getStatus() !== null
                && $event->getStatus() !== $status
            ) {
                $version++;

                $this->repository->updateStatus(
                    id: $state->getId(),
                    status: $event->getStatus(),
                    version: $version,
                );

                $status = $event->getStatus();
            }

            if ($comment !== null) {
                $this->repository->addComment(
                    id: $state->getId(),
                    comment: $comment,
                );
            }

            return new ApplyTicketEventResult(
                id: $state->getId(),
                version: $version,
                status: $status,
                comment: $comment,
            );
        });
    }
}
