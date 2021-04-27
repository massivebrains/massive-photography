<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [], $message = 'Request Successful', $statusCode = 200)
    {
        return response()->json([
            'status' => 'Successful',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function failed($errors = [], $statusCode = 400, $message = 'Request Failed')
    {
        return response()->json([
            'status' => 'Failed',
            'message' => $message,
            'data' => [
                'errors' => $errors
            ]
        ], $statusCode);
    }
}
