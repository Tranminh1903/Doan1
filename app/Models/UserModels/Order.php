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
        'total_amount',
        'paid_at'
    ];
}
