<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Ticket;

class Seat extends Model
{
    protected $table = 'seats';
    protected $primaryKey = 'seatID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['theaterID','verticalRow','horizontalRow','seatType','status'];

    public function theater()
    {
        return $this->belongsTo(MovieTheater::class, 'theaterID', 'theaterID');
    }

    // Một ghế có thể xuất hiện trong nhiều vé (mỗi vé là 1 suất khác)
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'seatID', 'seatID');
    }
}
