<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    protected $fillable = [
        'customer_user_id','code','status','total_amount','paid_at'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_user_id');
    }
    public function histories()
    {
        return $this->hasMany(OrderHistory::class, 'order_id');
    }
}
