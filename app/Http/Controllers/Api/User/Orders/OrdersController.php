<?php

namespace App\Http\Controllers\Api\User\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Jobs\Api\NotifyPhotographerOfDeclinedOrderJob;
use Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::whereUserId(Auth::user()->id)->get();
        return $this->success($orders);
    }

    public function status(Request $request, $id = 0, $action = 'approve')
    {
        $order = Order::where([
            'user_id' => Auth::user()->id,
            'id' => $id,
            'status' => 'awaiting_approval'
        ])->first();

        if (!$order) {
            return $this->failed(['code' => 'E12', 'message' => 'Invalid Order ID']);
        }

        if ($action == 'approve') {
            $order->update(['status' => 'approved']);
            OrderDetail::where(['order_id' => $order->id])->update(['status' => 'approved']);
    
            return $this->success($order, 'Order has been marked as approved successfully');
        }

        $order->update(['status' => 'declined']);
        OrderDetail::where(['order_id' => $order->id])->update(['status' => 'declined']);
        NotifyPhotographerOfDeclinedOrderJob::dispatch($order);

        return $this->success($order, 'Order has been marked as declined and photographer has been notified.');
    }
}
