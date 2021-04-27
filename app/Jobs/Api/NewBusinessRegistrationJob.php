<?php

namespace App\Jobs\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewBusinessRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $otp;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = 'Dear '.$this->user->name.' <br> <br> Use the OTP below to complete your registration. <br> <br> <h1>'.$this->otp.'</h1>';
        _email($this->user->email, 'You are one step away from activating your Massive Photography Account', $message);
    }
}
