<?php

declare(strict_types=1);

namespace Tests\Feature\Ticket;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_can_be_created(): void
    {
        $response = $this->withHeader(
            'X-API-Key',
            config('app.api_key'),
        )->postJson('/api/v1/tickets', [
            'title' => 'Test ticket',
            'description' => 'Test ticket description',
            'author_email' => 'test@example.com',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);

        $this->assertDatabaseHas('tickets', [
            'title' => 'Test ticket',
            'description' => 'Test ticket description',
            'author_email' => 'test@example.com',
            'status' => 'new',
            'version' => 1,
        ]);
    }

    public function test_ticket_creation_rejects_invalid_email(): void
    {
        $response = $this->withHeader(
            'X-API-Key',
            config('app.api_key'),
        )->postJson('/api/v1/tickets', [
            'title' => 'Test ticket',
            'description' => 'Test ticket description',
            'author_email' => 'invalid-email',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('error.code', 'validation_error');

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_ticket_creation_rejects_short_title(): void
    {
        $response = $this->withHeader(
            'X-API-Key',
            config('app.api_key'),
        )->postJson('/api/v1/tickets', [
            'title' => 'ab',
            'description' => 'Test ticket description',
            'author_email' => 'test@example.com',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('error.code', 'validation_error');

        $this->assertDatabaseCount('tickets', 0);
    }
}
