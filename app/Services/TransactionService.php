<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use App\Services\Interfaces\TransactionServiceInterface;

class TransactionService implements TransactionServiceInterface
{
    public function getUserTransactions(int $userId): Collection
    {
        return Transaction::with(['buyOrder', 'sellOrder', 'buyer', 'seller'])
            ->where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
