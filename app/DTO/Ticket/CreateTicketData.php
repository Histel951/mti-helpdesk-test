<?php

declare(strict_types = 1);

namespace App\DTO\Ticket;

final readonly class CreateTicketData
{
    public function __construct(
        private string $title,
        private string $description,
        private string $authorEmail,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            authorEmail: $data['author_email'],
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }
}
