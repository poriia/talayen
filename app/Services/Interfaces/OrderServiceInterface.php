<?php

namespace App\Services\Interfaces;

use App\Models\Order;

interface OrderServiceInterface
{
    public function createOrder(array $data, string $type, int $userId): Order;
    public function cancelOrder(int $orderId, int $userId): void;
}
