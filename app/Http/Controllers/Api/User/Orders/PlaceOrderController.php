<?php

namespace App\Http\Controllers\Api\User\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Jobs\Api\NewOrderNotificationJob;
use App\Http\Requests\Api\PlaceOrderRequest;

class PlaceOrderController extends Controller
{
    public function index(PlaceOrderRequest $request)
    {
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'reference' => _reference(),
            'instruction' => request('instruction')
        ]);

        foreach (request('products') as $row) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_name' => $row
            ]);
        }

        NewOrderNotificationJob::dispatch($order);
        return $this->success(['order_reference' => $order->reference], 'Your order has been placed successfully, you will be notified when preview images are ready.', 201);
    }
}
