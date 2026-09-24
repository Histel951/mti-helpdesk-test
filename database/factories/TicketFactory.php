<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-3 months', 'now');
        $updatedAt = fake()->dateTimeBetween($createdAt, 'now');

        return [
            'title' => fake()->sentence(fake()->numberBetween(3, 8)),
            'description' => fake()->paragraphs(
                fake()->numberBetween(1, 3),
                true,
            ),
            'author_email' => fake()->safeEmail(),
            'status' => fake()->randomElement(TicketStatus::cases()),
            'version' => 1,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
