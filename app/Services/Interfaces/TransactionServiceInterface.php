<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;

interface TransactionServiceInterface
{
    public function getUserTransactions(int $userId): Collection;
}
