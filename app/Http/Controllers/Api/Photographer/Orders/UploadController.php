<?php

namespace App\Http\Controllers\Api\Photographer\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;
use App\Models\OrderDetail;
use App\Http\Requests\Api\UploadRequest;

class UploadController extends Controller
{
    public function index(UploadRequest $request)
    {
        $order_detail = OrderDetail::where([
            'id' => request('order_detail_id'),
            'order_id' => request('order_id'),
        ])
        ->whereIn('status', ['pending', 'declined'])
        ->whereHas('order', function ($query) {
            $query->where(['photographer_id' => Auth::user()->id]);
        })
        ->first();

        if (!$order_detail) {
            return $this->failed(['code' => 'E11', 'message' => 'Invalid Order Detail ID'], 422);
        }

        $images['thumbnail_url'] = request()->file('thumbnail')->storeOnCloudinary()->getSecurePath();
        $images['image_url'] = request()->file('image')->storeOnCloudinary()->getSecurePath();

        if (!$images['thumbnail_url'] || !$images['image_url']) {
            return $this->failed(['code' => 'E11', 'message' => $response->error], 400, 'Could not upload images.');
        }
        
        $order_detail->update([
            'thumbnail_url' => $images['thumbnail_url'],
            'image_url' => $images['image_url'],
            'status' => 'awaiting_approval'
        ]);

        $order_detail->order->checkAllDetailsForAwaitingApproval();

        return $this->success($images, 'Images Uploaded successfully');
    }
}
