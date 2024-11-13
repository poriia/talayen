<?php

namespace App\Strategies;

use App\Models\User;
use App\DTOs\OrderDTO;
use App\Exceptions\OrderException;
use App\Strategies\Contracts\OrderTypeHandlerInterface;

class SellOrderHandler implements OrderTypeHandlerInterface
{
    public function validate(User $user, OrderDTO $orderDTO): void
    {
        if ($user->balance < $orderDTO->amount) {
            throw new OrderException('موجودی کافی نیست');
        }
    }

    public function deductBalance(User $user, OrderDTO $orderDTO): void
    {
        $user->balance -= $orderDTO->amount;
    }
}
