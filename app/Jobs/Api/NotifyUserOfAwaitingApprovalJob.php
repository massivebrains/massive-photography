<?php

namespace App\Jobs\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class NotifyUserOfAwaitingApprovalJob implements ShouldQueue
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
        $user = $this->order->user;
        $message = 'Dear '.$user->name.', <br> <br> Your order with Order Reference: '.$this->order->reference.' has been updated to Awaiting Approval. Find below links to preview thumbnails for this order: <br><br>';
        
        foreach ($this->order->order_details as $row) {
            $message .= $row->product_name.' => '.$row->thumbnail_url.'<br>';
        }

        $message .= '<br>Kindly login to your console to view the thumbnails uploaded for your reference.';
        _email($user->email, 'Order '.$this->order->reference. ' needs your approval', $message);
    }
}
