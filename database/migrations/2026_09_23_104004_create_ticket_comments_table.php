<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket_id');

            $table->string('author', 100);
            $table->text('message');
            $table->dateTime('created_at');

            $table->foreign('ticket_id', 'fk_ticket_comments_ticket')
                ->references('id')
                ->on('tickets')
                ->cascadeOnDelete();

            $table->index(
                ['ticket_id', 'created_at'],
                'idx_comments_ticket_created'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
