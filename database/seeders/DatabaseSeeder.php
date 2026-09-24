<?php

namespace Database\Seeders;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Ticket::factory()
            ->count(5)
            ->hasComments(2)
            ->create([
                'status' => TicketStatus::NEW,
            ]);

        Ticket::factory()
            ->count(5)
            ->hasComments(3)
            ->create([
                'status' => TicketStatus::IN_PROGRESS,
            ]);

        Ticket::factory()
            ->count(3)
            ->hasComments(1)
            ->create([
                'status' => TicketStatus::DONE,
            ]);

        Ticket::factory()
            ->count(2)
            ->create([
                'status' => TicketStatus::CLOSED,
            ]);
    }
}
