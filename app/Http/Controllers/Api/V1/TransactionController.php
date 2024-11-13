<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Services\Interfaces\TransactionServiceInterface;

class TransactionController extends ApiBaseController
{
    public function __construct(protected TransactionServiceInterface $transactionService) {}

    public function index(Request $request)
    {
        try {
            $transactions = $this->transactionService->getUserTransactions(auth()->id());
            return TransactionResource::collection($transactions)->additional([
                'meta' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطایی رخ داده است'], 500);
        }
    }
}
