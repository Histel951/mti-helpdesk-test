<?php

declare(strict_types=1);

namespace Tests\Feature\Ticket;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ApplyTicketEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_status_can_be_changed_with_comment(): void
    {
        $ticket = Ticket::factory()->create([
            'status' => TicketStatus::NEW,
            'version' => 1,
        ]);

        $response = $this->withHeader(
            'X-API-Key',
            config('app.api_key'),
        )->postJson(
            "/api/v1/tickets/{$ticket->id}/events",
            [
                'version' => 1,
                'status' => 'in_progress',
                'comment' => [
                    'author' => 'Danil',
                    'message' => 'working on the ticket',
                ],
            ],
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $ticket->id)
            ->assertJsonPath('data.status', 'in_progress')
            ->assertJsonPath('data.version', 2);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'in_progress',
            'version' => 2,
        ]);

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'author' => 'Danil',
            'message' => 'working on the ticket',
        ]);
    }

    public function test_event_is_rejected_when_version_is_outdated(): void
    {
        $ticket = Ticket::factory()->create([
            'status' => TicketStatus::NEW,
            'version' => 2,
        ]);

        $response = $this->withHeader(
            'X-API-Key',
            config('app.api_key'),
        )->postJson(
            "/api/v1/tickets/{$ticket->id}/events",
            [
                'version' => 1,
                'status' => 'in_progress',
                'comment' => [
                    'author' => 'Danil',
                    'message' => 'update ticket',
                ],
            ],
        );

        $response
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'version_conflict');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'new',
            'version' => 2,
        ]);

        $this->assertDatabaseCount('ticket_comments', 0);
    }

    public function test_comment_can_be_added_without_changing_version(): void
    {
        $ticket = Ticket::factory()->create([
            'status' => TicketStatus::NEW,
            'version' => 1,
        ]);

        $response = $this->withHeader(
            'X-API-Key',
            config('app.api_key'),
        )->postJson(
            "/api/v1/tickets/{$ticket->id}/events",
            [
                'version' => 1,
                'comment' => [
                    'author' => 'Danil',
                    'message' => 'comment',
                ],
            ],
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $ticket->id)
            ->assertJsonPath('data.status', 'new')
            ->assertJsonPath('data.version', 1);

        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'author' => 'Danil',
            'message' => 'comment',
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'new',
            'version' => 1,
        ]);
    }
}
