<?php

namespace App\Factories;

use App\Exceptions\OrderException;
use App\Strategies\BuyOrderHandler;
use App\Strategies\SellOrderHandler;
use App\Strategies\Contracts\OrderTypeHandlerInterface;

class OrderTypeHandlerFactory
{
    public static function getHandler(string $type): OrderTypeHandlerInterface
    {
        switch ($type) {
            case 'buy':
                return app(BuyOrderHandler::class);
            case 'sell':
                return app(SellOrderHandler::class);
            default:
                throw new OrderException('نوع سفارش نامعتبر است');
        }
    }
}
