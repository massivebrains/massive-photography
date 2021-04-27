<?php

namespace App\Http\Controllers\Api\User\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::whereUserId(Auth::user()->id)->get();
        return $this->success($orders);
    }
}
