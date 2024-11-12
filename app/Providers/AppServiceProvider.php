<?php

namespace App\Providers;

use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\OrderServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
