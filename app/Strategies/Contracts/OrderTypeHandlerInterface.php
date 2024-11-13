<?php

namespace App\Strategies\Contracts;

use App\Models\User;
use App\DTOs\OrderDTO;

interface OrderTypeHandlerInterface
{
    public function validate(User $user, OrderDTO $orderDTO): void;
    public function deductBalance(User $user, OrderDTO $orderDTO): void;
}
