<?php

namespace App\Http\Resources;

use App\UseCases\Ticket\DTO\ApplyTicketEventResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ApplyTicketEventResult $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->getId(),
            'version' => $resource->getVersion(),
            'status' => $resource->getStatus()->value,
            'comment' => $resource->getComment() !== null
                ? [
                    'author' => $resource->getComment()->getAuthor(),
                    'message' => $resource->getComment()->getMessage(),
                ]
                : null,
        ];
    }
}
