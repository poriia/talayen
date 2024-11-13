<?php

namespace App\Providers;

use App\Services\OrderService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Interfaces\TransactionServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
