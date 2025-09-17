<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_user_id',
        'order_code',
        'status',
        'amount',
        'paid_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_user_id', 'user_id');
    }
}
