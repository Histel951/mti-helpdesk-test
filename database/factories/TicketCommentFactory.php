<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketComment>
 */
class TicketCommentFactory extends Factory
{
    protected $model = TicketComment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'author' => fake()->name(),
            'message' => fake()->paragraph(),
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
