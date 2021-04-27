<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public $guarded = ['updated_at'];
    
    public function getImageUrlAttribute()
    {
        if ($this->status != 'approved') {
            return null;
        }
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order')->withDefault();
    }
}
