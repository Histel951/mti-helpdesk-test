<?php

namespace App\Http\Resources;

use App\DTO\Ticket\TicketListItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var TicketListItem $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->getId(),
            'title' => $resource->getTitle(),
            'status' => $resource->getStatus()->value,
            'created_at' => $resource->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $resource->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
