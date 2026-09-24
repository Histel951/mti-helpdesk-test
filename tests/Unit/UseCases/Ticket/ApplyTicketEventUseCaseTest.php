<?php

declare(strict_types=1);

namespace Tests\Unit\UseCases\Ticket;

use App\DTO\Ticket\TicketComment;
use App\DTO\Ticket\TicketState;
use App\Enums\TicketStatus;
use App\Mappers\Ticket\TicketMapper;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\UseCases\Ticket\ApplyTicketEventUseCase;
use App\UseCases\Ticket\DTO\TicketEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ApplyTicketEventUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_change_increments_version(): void
    {
        $repository = $this->createMock(TicketRepositoryInterface::class);
        $mapper = $this->createMock(TicketMapper::class);

        $event = new TicketEvent(
            id: 1,
            version: 1,
            status: TicketStatus::IN_PROGRESS,
        );

        $state = new TicketState(
            id: 1,
            version: 1,
            status: TicketStatus::NEW,
        );

        $repository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with(1)
            ->willReturn((object) [
                'id' => 1,
                'version' => 1,
                'status' => TicketStatus::NEW->value,
            ]);

        $mapper
            ->expects($this->once())
            ->method('toState')
            ->willReturn($state);

        $repository
            ->expects($this->once())
            ->method('updateStatus')
            ->with(
                1,
                TicketStatus::IN_PROGRESS,
                2,
            );

        $useCase = new ApplyTicketEventUseCase(
            repository: $repository,
            mapper: $mapper,
        );

        $result = $useCase->execute($event);

        $this->assertSame(1, $result->getId());
        $this->assertSame(2, $result->getVersion());
        $this->assertSame(
            TicketStatus::IN_PROGRESS,
            $result->getStatus(),
        );
    }

    public function test_same_status_does_not_increment_version(): void
    {
        $repository = $this->createMock(TicketRepositoryInterface::class);
        $mapper = $this->createMock(TicketMapper::class);

        $event = new TicketEvent(
            id: 1,
            version: 3,
            status: TicketStatus::IN_PROGRESS,
        );

        $state = new TicketState(
            id: 1,
            version: 3,
            status: TicketStatus::IN_PROGRESS,
        );

        $repository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with(1)
            ->willReturn((object) [
                'id' => 1,
                'version' => 3,
                'status' => TicketStatus::IN_PROGRESS->value,
            ]);

        $mapper
            ->expects($this->once())
            ->method('toState')
            ->willReturn($state);

        $repository
            ->expects($this->never())
            ->method('updateStatus');

        $useCase = new ApplyTicketEventUseCase(
            repository: $repository,
            mapper: $mapper,
        );

        $result = $useCase->execute($event);

        $this->assertSame(3, $result->getVersion());
        $this->assertSame(
            TicketStatus::IN_PROGRESS,
            $result->getStatus(),
        );
    }

    public function test_comment_only_does_not_increment_version(): void
    {
        $repository = $this->createMock(TicketRepositoryInterface::class);
        $mapper = $this->createMock(TicketMapper::class);

        $event = new TicketEvent(
            id: 1,
            version: 5,
            status: null,
        );

        $comment = TicketComment::fromArray([
            'author' => 'Danil',
            'message' => 'comment',
        ]);

        $state = new TicketState(
            id: 1,
            version: 5,
            status: TicketStatus::NEW,
        );

        $repository
            ->expects($this->once())
            ->method('findByIdForUpdate')
            ->with(1)
            ->willReturn((object) [
                'id' => 1,
                'version' => 5,
                'status' => TicketStatus::NEW->value,
            ]);

        $mapper
            ->expects($this->once())
            ->method('toState')
            ->willReturn($state);

        $repository
            ->expects($this->once())
            ->method('addComment')
            ->with(1, $comment);

        $repository
            ->expects($this->never())
            ->method('updateStatus');

        $useCase = new ApplyTicketEventUseCase(
            repository: $repository,
            mapper: $mapper,
        );

        $result = $useCase->execute(
            event: $event,
            comment: $comment,
        );

        $this->assertSame(5, $result->getVersion());
        $this->assertSame(
            TicketStatus::NEW,
            $result->getStatus(),
        );
        $this->assertSame($comment, $result->getComment());
    }
}
