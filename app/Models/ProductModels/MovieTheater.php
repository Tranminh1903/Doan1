<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Showtime;

class MovieTheater extends Model
{
    protected $table = 'movie_theater';
    protected $primaryKey = 'theaterID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['roomName','seat_map_id'];

    // 1 phòng có nhiều ghế
    public function seats()
    {
        return $this->hasMany(Seat::class, 'theaterID', 'theaterID');
    }

    // 1 phòng có nhiều suất chiếu
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'theaterID', 'theaterID');
    }
}
