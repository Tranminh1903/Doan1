<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','showtime_id','quantity','total_amount','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function customerPromotions()
    {
        return $this->hasMany(CustomerPromotion::class);
    }
}
