<?php

namespace App\Http\Controllers\Api\Admin\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;
use App\Http\Requests\Api\OrderFilterRequest;
use App\Jobs\Api\NotifyPhotographerOfOrderAssignmentJob;
use App\Http\Requests\Api\AssignPhotographerRequest;

class OrdersController extends Controller
{
    public function index(OrderFilterRequest $request)
    {
        $orders = Order::query();

        if (request('status')) {
            $orders->whereStatus(request('status'));
        }

        return $this->success($orders->get());
    }

    public function assignPhotographer(AssignPhotographerRequest $request)
    {
        $order = Order::where([
            'id' => request('order_id'),
            'status' => 'pending'
        ])->first();

        if (!$order) {
            return $this->failed(['code' => 'E10', 'message' => 'Invalid Order Reference'], 400);
        }

        $order->update(['photographer_id' => request('photographer_id')]);
        NotifyPhotographerOfOrderAssignmentJob::dispatch($order);

        return $this->success(['reference' => $order->reference], 'Photographer has been successfuly assigned to order.');
    }
}
