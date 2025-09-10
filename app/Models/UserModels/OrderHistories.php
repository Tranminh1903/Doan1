<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;

class OrderHistories extends Model
{
    protected $fillable = ['order_id','status','note','changed_at'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
