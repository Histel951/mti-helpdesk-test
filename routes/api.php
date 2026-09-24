<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::post('/v1/tickets', [TicketController::class, 'store']);
    Route::get('/v1/tickets/{ticket}', [TicketController::class, 'show']);
    Route::post('/v1/tickets/{ticket}/events', [TicketController::class, 'applyEvent']);
    Route::get('/v1/tickets', [TicketController::class, 'index']);
});
