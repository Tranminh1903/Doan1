<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProductModels\Ticket;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'orderID';
    public $incrementing = true;
    protected $keyType = 'int';
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
    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'orderID', 'orderID');
    }
}

