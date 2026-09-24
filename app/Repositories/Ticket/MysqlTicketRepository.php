<?php

declare(strict_types=1);

namespace App\Repositories\Ticket;

use App\DTO\Ticket\CreateTicketData;
use App\DTO\Ticket\TicketComment;
use App\DTO\Ticket\TicketIndexQuery;
use App\DTO\Ticket\TicketPaginateResult;
use App\Enums\TicketStatus;
use App\Exceptions\TicketNotFoundException;
use Illuminate\Support\Facades\DB;

final class MysqlTicketRepository implements TicketRepositoryInterface
{
    public function findByIdForUpdate(int $id): object
    {
        $ticket = DB::selectOne(
            '
            SELECT id, version, status
            FROM tickets
            WHERE id = ?
            FOR UPDATE
            ',
            [$id],
        );

        if ($ticket === null) {
            throw new TicketNotFoundException($id);
        }

        return $ticket;
    }

    public function updateStatus(int $id, TicketStatus $status, int $version): void
    {
        DB::update(
            '
            UPDATE tickets
            SET
                status = ?,
                version = ?,
                updated_at = ?
            WHERE id = ?
            ',
            [
                $status->value,
                $version,
                now(),
                $id,
            ],
        );
    }

    public function addComment(int $id, TicketComment $comment): void
    {
        DB::insert(
            '
            INSERT INTO ticket_comments (
                ticket_id,
                author,
                message,
                created_at
            )
            VALUES (?, ?, ?, ?)
            ',
            [
                $id,
                $comment->getAuthor(),
                $comment->getMessage(),
                now(),
            ],
        );
    }

    public function paginate(
        TicketIndexQuery $query,
    ): TicketPaginateResult {
        $conditions = [];
        $bindings = [];

        if ($query->getStatus() !== null) {
            $conditions[] = 'status = ?';
            $bindings[] = $query->getStatus()->value;
        }

        if ($query->getSearch() !== null) {
            $conditions[] = '(title LIKE ? OR description LIKE ?)';

            $search = '%' . $query->getSearch() . '%';

            $bindings[] = $search;
            $bindings[] = $search;
        }

        $where = $conditions !== []
            ? 'WHERE ' . implode(' AND ', $conditions)
            : '';

        $sort = $query->getSort();

        $total = DB::selectOne(
            "
        SELECT COUNT(*) AS aggregate
        FROM tickets
        {$where}
        ",
            $bindings,
        );

        $offset = ($query->getPage() - 1) * $query->getPerPage();

        $rows = DB::select(
            "
        SELECT
            id,
            title,
            description,
            status,
            version,
            created_at,
            updated_at
        FROM tickets
        {$where}
        ORDER BY {$sort->getColumn()} {$sort->getDirection()}
        LIMIT ? OFFSET ?
        ",
            [
                ...$bindings,
                $query->getPerPage(),
                $offset,
            ],
        );

        return new TicketPaginateResult(
            rows: $rows,
            total: (int) $total->aggregate,
        );
    }

    public function create(CreateTicketData $data): int
    {
        DB::insert(
            '
        INSERT INTO tickets (
            title,
            description,
            author_email,
            status,
            version,
            created_at,
            updated_at
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ',
            [
                $data->getTitle(),
                $data->getDescription(),
                $data->getAuthorEmail(),
                TicketStatus::NEW->value,
                1,
                now(),
                now(),
            ],
        );

        return (int) DB::getPdo()->lastInsertId();
    }
}
