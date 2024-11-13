<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class CancelOrderDTO extends DataTransferObject
{
    public int $orderId;
    public int $userId;
}