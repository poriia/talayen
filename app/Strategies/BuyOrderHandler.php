<?php

namespace App\Strategies;

use App\Models\User;
use App\DTOs\OrderDTO;
use App\Exceptions\OrderException;
use App\Strategies\Contracts\OrderTypeHandlerInterface;

class BuyOrderHandler implements OrderTypeHandlerInterface
{
    public function validate(User $user, OrderDTO $orderDTO): void
    {
        $totalPrice = $orderDTO->amount * $orderDTO->price;
        if ($user->balance < $totalPrice) {
            throw new OrderException('موجودی کافی نیست');
        }
    }

    public function deductBalance(User $user, OrderDTO $orderDTO): void
    {
        $totalPrice = $orderDTO->amount * $orderDTO->price;
        $user->balance -= $totalPrice;
    }
}
