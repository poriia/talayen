<?php
namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class TransactionRequestDTO extends DataTransferObject
{
    public int $userId;
    public int $perPage;
}
