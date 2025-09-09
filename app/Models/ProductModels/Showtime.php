<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id','screening_room_id','start_time','end_time','price'];

    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function room()
    {
        return $this->belongsTo(MovieTheater::class, 'screening_room_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
