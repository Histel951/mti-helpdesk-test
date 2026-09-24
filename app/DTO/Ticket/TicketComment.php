<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

final readonly class TicketComment
{
    public function __construct(
        private string $message,
        private string $author,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            message: $data['message'],
            author: $data['author'],
        );
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
}
