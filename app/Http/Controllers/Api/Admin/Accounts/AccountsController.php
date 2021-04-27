<?php

namespace App\Http\Controllers\Api\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereStatus('active');

        if (request('type')) {
            $users->whereType(request('type'));
        } else {
            $users->whereIn('type', ['user', 'photographer']);
        }
        
        return $this->success($users->get());
    }
}
