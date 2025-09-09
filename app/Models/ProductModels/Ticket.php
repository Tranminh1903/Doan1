<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','showtime_id','price','seat_no','qr_code','issued_at','status'];

    protected $casts = ['issued_at' => 'datetime'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }
    public function chair()
    {
        return $this->belongsTo(Chair::class, 'seat_no', 'id');
    }
}
