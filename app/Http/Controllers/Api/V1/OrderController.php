<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\Order;
use App\DTOs\OrderDTO;
use App\DTOs\CancelOrderDTO;
use App\Exceptions\OrderException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderBuyRequest;
use App\Http\Requests\OrderSellRequest;
use App\Services\Interfaces\OrderServiceInterface;

class OrderController extends Controller
{
    public function __construct(
        protected OrderServiceInterface $orderService
    ) {}

    public function buy(OrderBuyRequest $request)
    {
        return $this->handleOrder($request, 'buy');
    }

    public function sell(OrderSellRequest $request)
    {
        return $this->handleOrder($request, 'sell');
    }

    private function handleOrder($request, string $type)
    {
        $orderDTO = new OrderDTO($request);

        try {
            $order = $this->orderService->createOrder($orderDTO->toArray(), $type, auth()->id());
            return response()->json(['message' => 'سفارش با موفقیت ثبت شد', 'order' => $order], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel($id)
    {
        $cancelOrderDTO = new CancelOrderDTO([
            'orderId' => $id,
            'userId' => auth()->id(),
        ]);

        try {
            $this->orderService->cancelOrder($cancelOrderDTO->orderId, $cancelOrderDTO->userId);
            return response()->json(['message' => 'سفارش با موفقیت لغو شد'], 200);
        } catch (OrderException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'خطایی رخ داده است'], 500);
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->user())->get();
        return response()->json($orders, 200);
    }
}
