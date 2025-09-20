<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Movie;
use App\Models\ProductModels\SeatHold;
class Showtime extends Model
{
    protected $table = 'showtime';
    protected $primaryKey = 'showtimeID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $dates = ['startTime','endTime'];
    protected $fillable = ['movieID','theaterID','startTime','endTime','basePrice'];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieID', 'movieID');
    }

    public function theater()
    {
        return $this->belongsTo(MovieTheater::class, 'theaterID', 'theaterID');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'showtimeID', 'showtimeID');
    }
    public function reservedSeats()
    {
        return $this->belongsToMany(
            Seat::class,          
            'tickets',            
            'showtimeID',         
            'seatID',             
            'showtimeID',         
            'seatID'              
        );
    }
    public function holds()
    {
        return $this->hasMany(SeatHold::class, 'showtimeID', 'showtimeID');
    }
    public function availableSeats()
    {
        $reserved = $this->reservedSeats()->pluck('seats.seatID');
        $held = method_exists($this, 'holds')
            ? $this->holds()->where('expires_at', '>', now())->pluck('seatID'): collect([]);

        return Seat::where('theaterID', $this->theaterID)
            ->where('status', 'active')
            ->whereNotIn('seatID', $reserved)
            ->when($held->isNotEmpty(), fn($q) => $q->whereNotIn('seatID', $held))
            ->orderBy('verticalRow')->orderBy('horizontalRow');
    }
}
