<?php

namespace App\Http\Controllers\Api\Photographer\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::wherePhotographerId(Auth::user()->id)->get();
        return $this->success($orders);
    }
}
