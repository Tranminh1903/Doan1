<?php

namespace App\Models\ProductModels;

use App\Models\UserModels\User;
use App\Models\UserModels\Order;
use Illuminate\Database\Eloquent\Model;

class SeatHold extends Model
{
    protected $table = 'seat_holds';
    protected $fillable = ['showtimeID','seatID','user_id','expires_at','orderID','status'];
    protected $casts = ['expires_at' => 'datetime'];

    public function showtime(){ return $this->belongsTo(Showtime::class, 'showtimeID', 'showtimeID'); }
    public function seat(){ return $this->belongsTo(Seat::class, 'seatID', 'seatID'); }
    public function user(){ return $this->belongsTo(User::class, 'user_id', 'id'); }
    public function order(){ return $this->belongsTo(Order::class, 'orderID', 'orderID'); }
    public function scopeActive($q){ return $q->where('expires_at', '>', now()); }
    public function scopeExpired($q){
        return $q->whereIn('status', ['held','pending'])
                 ->where('expires_at', '<', now());
    }
    public function getIsExpiredAttribute(){
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }
}
