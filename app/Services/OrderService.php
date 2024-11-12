<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Transaction;
use App\Tools\FeeCalculator;
use App\Exceptions\OrderException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Services\Interfaces\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{

    public function __construct(protected OrderServiceInterface $orderService) {}

    public function createOrder(array $data, string $type, int $userId): Order
    {
        return DB::transaction(function () use ($data, $type, $userId) {
            $user = User::findOrFail($userId);

            if ($type === 'buy') {
                $totalPrice = $data['amount'] * $data['price'];
                if ($user->balance < $totalPrice) {
                    throw new \Exception('موجودی کافی نیست');
                }
                $user->balance -= $totalPrice;
            } elseif ($type === 'sell') {
                if ($user->balance < $data['amount']) {
                    throw new \Exception('موجودی کافی نیست');
                }
                $user->balance -= $data['amount'];
            } else {
                throw new \Exception('نوع سفارش نامعتبر است');
            }

            $user->save();

            $order = Order::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $data['amount'],
                'price' => $data['price'],
                'remaining_amount' => $data['amount'],
                'status' => 'open',
            ]);

            $redisKey = $type === 'buy' ? 'buy_orders' : 'sell_orders';
            $score = $type === 'buy' ? -$data['price'] : $data['price'];
            Redis::zadd($redisKey, $score, $order->id);

            $this->processOrders();

            return $order;
        });
    }

    public function cancelOrder(int $orderId, int $userId): void
    {
        DB::transaction(function () use ($orderId, $userId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->where('status', 'open')
                ->first();

            if (!$order) {
                throw new OrderException('سفارش یافت نشد یا قابل لغو نیست');
            }

            $user = User::findOrFail($userId);

            if ($order->type === 'buy') {
                $refund = $order->remaining_amount * $order->price;
                $user->balance += $refund;
            } elseif ($order->type === 'sell') {
                $user->balance += $order->remaining_amount;
            }

            $user->save();

            $order->status = 'cancelled';
            $order->save();

            $redisKey = $order->type === 'buy' ? 'buy_orders' : 'sell_orders';
            Redis::zrem($redisKey, $order->id);
        });
    }

    private function processOrders(): void
    {
        while (true) {
            $buyOrderId = Redis::zrevrange('buy_orders', 0, 0)[0] ?? null;
            $sellOrderId = Redis::zrange('sell_orders', 0, 0)[0] ?? null;

            if (!$buyOrderId || !$sellOrderId) {
                break;
            }

            $buyOrder = Order::find($buyOrderId);
            $sellOrder = Order::find($sellOrderId);

            if ($buyOrder->price < $sellOrder->price) {
                break;
            }

            $transactionAmount = min($buyOrder->remaining_amount, $sellOrder->remaining_amount);
            $price = $sellOrder->price;

            $fee = FeeCalculator::calculate($transactionAmount, $price);

            $buyOrder->remaining_amount -= $transactionAmount;
            $sellOrder->remaining_amount -= $transactionAmount;

            if ($buyOrder->remaining_amount == 0) {
                $buyOrder->status = 'completed';
                Redis::zrem('buy_orders', $buyOrder->id);
            } else {
                Redis::zadd('buy_orders', -$buyOrder->price, $buyOrder->id);
            }

            if ($sellOrder->remaining_amount == 0) {
                $sellOrder->status = 'completed';
                Redis::zrem('sell_orders', $sellOrder->id);
            } else {
                Redis::zadd('sell_orders', $sellOrder->price, $sellOrder->id);
            }

            $buyOrder->save();
            $sellOrder->save();

            $transaction = Transaction::create([
                'buy_order_id' => $buyOrder->id,
                'sell_order_id' => $sellOrder->id,
                'buyer_id' => $buyOrder->user_id,
                'seller_id' => $sellOrder->user_id,
                'amount' => $transactionAmount,
                'price' => $price,
                'fee' => $fee,
            ]);

            $buyer = User::find($buyOrder->user_id);
            $seller = User::find($sellOrder->user_id);

            $buyer->balance += $transactionAmount;
            $buyer->save();

            $seller->balance += ($transactionAmount * $price) - $fee;
            $seller->save();
        }
    }
}
