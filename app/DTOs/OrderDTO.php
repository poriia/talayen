<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class OrderDTO extends DataTransferObject
{
    public float $amount;
    public float $price;
}
