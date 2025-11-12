<?php
namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Showtime;
use App\Models\ProductModels\Ticket;
use App\Models\UserModels\Promotion;
class Order extends Model
{
    protected $fillable = [
    'showtimeID',
    'order_code',
    'promotion_code',
    'seats',
    'status',
    'username',
    'amount',
    ];
    
    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtimeID', 'showtimeID');
    }
    public function tickets()
    {
    return $this->hasMany(Ticket::class, 'showtimeID', 'showtimeID');
    }
    public function scopePaid($q)
    {
        $table = $q->getModel()->getTable();
        return $q->where("$table.status", 'paid');
    }
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_code', 'code');
    }

}
