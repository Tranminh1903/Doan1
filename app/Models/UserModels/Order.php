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


}
