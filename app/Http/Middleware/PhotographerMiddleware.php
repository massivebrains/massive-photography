<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class PhotographerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'photographer') {
                return $next($request);
            }
        }

        return response()->json([
            'status' => 'Failed',
            'message' => 'Unauthorized',
            'data' => [
                'errors' => [
                    'code' => 'E09',
                    'message' => 'Unauthorized request'
                ]
            ]
        ], 403);
    }
}
