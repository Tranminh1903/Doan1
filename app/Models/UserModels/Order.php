<?php
namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_user_id',
        'order_code',
        'seats',
        'status',
        'amount',
        'paid_at',
    ];
}
