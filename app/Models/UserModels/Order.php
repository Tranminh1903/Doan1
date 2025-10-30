<?php
namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Showtime;
use App\Models\ProductModels\Ticket;
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

}
