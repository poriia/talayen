<?php

namespace App\Providers;

use App\Services\OrderService;
use App\Strategies\BuyOrderHandler;
use App\Strategies\SellOrderHandler;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;
use App\Factories\OrderTypeHandlerFactory;
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
        $this->app->bind(OrderTypeHandlerFactory::class, function ($app) {
            return new OrderTypeHandlerFactory(
                $app->make(BuyOrderHandler::class),
                $app->make(SellOrderHandler::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
