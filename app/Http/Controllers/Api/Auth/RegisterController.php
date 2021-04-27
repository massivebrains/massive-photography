<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use App\Jobs\Api\NewBusinessRegistrationJob;
use App\Jobs\Api\NewBusinessActivatedJob;
use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index(RegisterRequest $request)
    {
        $otp = mt_rand(111111, 999999);

        $user = User::create([

            'name' => request('name'),
            'email' => request('email'),
            'phone' => _to_phone(request('phone')),
            'password' => bcrypt(request('password')),
            'address' => request('address'),
            'type' => 'business',
            'otp' => md5($otp),
            'status' => 'inactive'
        ]);

        NewBusinessRegistrationJob::dispatch($user, $otp);

        return $this->success([], 'Your registration is successful, an email has been sent to you to confirm your account.', 201);
    }

    public function activate($otp = null)
    {
        if (!$otp) {
            return $this->failed(['code' => 'E03', 'message' => 'Invalid OTP'], 422);
        }
        
        $user = User::where([
            'otp' => md5($otp),
            'type' => 'business',
            'status' => 'inactive'
        ])->first();

        if (!$user) {
            return $this->failed(['code' => 'E04', 'message' => 'Invalid OTP']);
        }

        $user->update([
            'email_verified_at' => now()->format('Y-m-d H:i:s'),
            'otp' => null,
            'status' => 'active'
        ]);

        NewBusinessActivatedJob::dispatch($user);
        _log($user->name . ' successfully verified email address', $user);

        return $this->success([], 'Email has been successfully verified');
    }
}
