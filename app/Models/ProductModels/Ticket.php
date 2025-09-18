<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';
    protected $primaryKey = 'ticketID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'orderID','showtimeID','seatID','price','status','qr_token','issueAt','refund_reason'
    ];
    protected $casts = [
        'issueAt' => 'datetime',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtimeID', 'showtimeID');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seatID', 'seatID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'orderID');
    }
}
