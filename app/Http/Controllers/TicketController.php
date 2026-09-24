<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\DTO\Ticket\CreateTicketData;
use App\DTO\Ticket\TicketComment;
use App\DTO\Ticket\TicketIndexQuery;
use App\Http\Requests\IndexTicketRequest;
use App\Http\Requests\StoreTicketEventRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketCreatedResource;
use App\Http\Resources\TicketEventResource;
use App\Http\Resources\TicketListResponseResource;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\UseCases\Ticket\ApplyTicketEventUseCase;
use App\UseCases\Ticket\CreateTicketUseCase;
use App\UseCases\Ticket\DTO\TicketEvent;
use App\UseCases\Ticket\ListTicketsUseCase;

class TicketController extends Controller
{
    public function store(
        StoreTicketRequest  $request,
        CreateTicketUseCase $useCase,
    ): TicketCreatedResource
    {
        return new TicketCreatedResource(
            $useCase->execute(
                CreateTicketData::fromArray($request->validated())
            )
        );
    }

    public function show(Ticket $ticket): TicketResource
    {
        return new TicketResource(
            $ticket->load('comments'),
        );
    }

    public function index(
        IndexTicketRequest $request,
        ListTicketsUseCase $useCase,
    ): TicketListResponseResource
    {
        $query = TicketIndexQuery::fromArray(
            $request->validated(),
        );

        return new TicketListResponseResource(
            $useCase->execute($query),
        );
    }

    public function applyEvent(
        int                     $id,
        StoreTicketEventRequest $request,
        ApplyTicketEventUseCase $useCase,
    ): TicketEventResource
    {
        $data = $request->validated();

        $event = TicketEvent::fromArray([
            'id' => $id,
            'version' => $data['version'],
            'status' => $data['status'] ?? null,
        ]);

        $comment = isset($data['comment'])
            ? TicketComment::fromArray($data['comment'])
            : null;

        return new TicketEventResource(
            $useCase->execute(
                event: $event,
                comment: $comment,
            )
        );
    }
}
