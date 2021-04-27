<?php

namespace App\Jobs\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class NotifyPhotographerOfOrderAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $photographer = $this->order->photographer;
        $message = 'Dear '.$photographer->name.', <br> <br> An order with reference '.$this->order->reference.' has been assigned to you. Kindly login to your console for more details.';
        _email($photographer->email, 'New Order Assigned to you. Reference: '.$this->order->reference, $message);
    }
}
