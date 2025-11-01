<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Showtime;

class MovieTheater extends Model
{
    protected $table = 'movie_theaters';
    protected $primaryKey = 'theaterID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'roomName',
        'seat_map_id',
        'capacity',
        'status',
    ];
    protected $cast = [
        'capacity' => 'integer',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'theaterID', 'theaterID');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'theaterID', 'theaterID');
    }
}
