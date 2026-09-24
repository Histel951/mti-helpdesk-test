<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'author_email', 'status'])]
/**
 * @property string $title
 * @property string $description
 * @property string $author_email
 * @property TicketStatus $status
 */
class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $casts = [
        'status' => TicketStatus::class,
        'version' => 'integer',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }
}
