<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'buy_order_id' => $this->buy_order_id,
            'sell_order_id' => $this->sell_order_id,
            'buyer' => new UserResource($this->buyer),
            'seller' => new UserResource($this->seller),
            'amount' => $this->amount,
            'price' => $this->price,
            'fee' => $this->fee,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
