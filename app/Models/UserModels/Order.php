<?php
namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'showtimeID',
    'order_code',
    'seats',
    'status',
    'username',
    'amount',
    ];
    
    public function showtime()
    {
        return $this->belongsTo(\App\Models\ProductModels\Showtime::class, 'showtimeID', 'showtimeID');
    }
    public function tickets()
    {
    return $this->hasMany(\App\Models\ProductModels\Ticket::class, 'showtimeID', 'showtimeID');
    }


}
