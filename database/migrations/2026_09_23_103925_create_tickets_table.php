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
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 200);
            $table->text('description');
            $table->string('author_email', 255);

            $table->enum('status', [
                'new',
                'in_progress',
                'done',
                'closed',
            ])->default('new');

            $table->integer('version')->default(1);

            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->index(
                ['status', 'created_at'],
                'idx_tickets_status_created'
            );

            $table->index(
                'updated_at',
                'idx_tickets_updated'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
