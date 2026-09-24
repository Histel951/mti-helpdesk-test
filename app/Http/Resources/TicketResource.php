<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ticket
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'author_email' => $this->author_email,
            'status' => $this->status->value,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'comments' => TicketCommentResource::collection(
                $this->whenLoaded('comments')
            ),
        ];
    }
}
