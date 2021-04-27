<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    public function createSecretAdminAccount()
    {
        if (User::whereType('admin')->count() > 0) {
            return;
        }
                 
        User::create([

            'name' => 'Administrator',
            'email' => 'admin@massive-photography.io',
            'phone' => '2348175020329',
            'status' => 'active',
            'type' => 'admin',
            'password' => bcrypt(env('DEFAULT_ADMIN_PASSWORD')),
            'api_token' => Str::uuid()
        ]);

        return true;
    }

    public function index(LoginRequest $request)
    {
        $this->createSecretAdminAccount();
        
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();

            _log($user->name . ' Logged In successfully', $user);

            $user->update(['api_token' => Str::uuid()]);
            $last_login_at = $user->last_login_at;
            $current_login_at = now()->format('Y-m-d H:i:s');
            $user->update(['last_login_at' => $current_login_at]);
            _log($user->name . ' Last Login Date changed from '. $last_login_at. ' to '. $current_login_at, $user);

            if ($user->status == 'inactive') {
                $error = 'Your account is currently inactive.';
                _log($user->name . ' logged in successfully but account is currently inactive', $user);

                return $this->failed(['code' => 'E02', 'message' => $error], 422);
            }

            return $this->success(Auth::user());
        }

        return $this->failed(['code' => 'E01', 'message' => 'Invalid Email or Password'], 400);
    }
}
