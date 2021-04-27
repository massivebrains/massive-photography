<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\Api\NotifyUserOfAwaitingApprovalJob;

class Order extends Model
{
    use HasFactory;

    public $guarded = ['updated_at'];
    public $hidden = ['photographer_id'];
    public $appends = ['products'];

    public function getProductsAttribute()
    {
        return $this->order_details->count();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function photographer()
    {
        return $this->belongsTo('App\Models\User', 'photographer_id')->withDefault();
    }

    public function order_details()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }

    public function checkAllDetailsForAwaitingApproval()
    {
        $not_awaiting_approval = $this->order_details()->where('status', '!=', 'awaiting_approval')->count();

        if ($not_awaiting_approval > 0) {
            return;
        }

        $this->update(['status' => 'awaiting_approval']);
        NotifyUserOfAwaitingApprovalJob::dispatch($this);
    }
}
