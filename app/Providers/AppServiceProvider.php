<?php

namespace App\Providers;

use App\Repositories\Ticket\MysqlTicketRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TicketRepositoryInterface::class,
            MysqlTicketRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
