<?php

namespace App\Http\Resources;

use App\UseCases\Ticket\DTO\TicketListResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketListResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var TicketListResult $resource */
        $resource = $this->resource;

        return [
            'page' => $resource->getPage(),
            'per_page' => $resource->getPerPage(),
            'total' => $resource->getTotal(),
            'items' => TicketListResource::collection(
                $resource->getItems(),
            ),
        ];
    }
}
