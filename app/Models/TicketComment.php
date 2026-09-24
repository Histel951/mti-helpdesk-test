<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['ticket_id', 'author', 'message'])]
/**
 * @property int $ticket_id
 * @property string $author
 * @property string $message
 */
class TicketComment extends Model
{
    use HasFactory;

    protected $table = 'ticket_comments';

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
