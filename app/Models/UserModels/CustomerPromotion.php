<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerPromotion extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','promotion_id','order_id','status','received_at','used_at','amount_applied'];
    protected $casts = ['received_at' => 'datetime','used_at' => 'datetime'];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
