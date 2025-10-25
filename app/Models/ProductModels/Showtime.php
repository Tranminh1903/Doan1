<?php

namespace App\Models\ProductModels;

use App\Models\UserModels\Order;
use App\Models\ProductModels\Seat;
use App\Models\ProductModels\Movie;
use App\Models\ProductModels\Ticket;
use App\Models\ProductModels\SeatHold;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\MovieTheater;

class Showtime extends Model
{
    protected $table = 'showtime';
    protected $primaryKey = 'showtimeID';
    public $incrementing = true;
    protected $keyType = 'int';

    // Khai báo cột ngày tháng
    protected $casts = [
        'startTime' => 'datetime',
        'endTime' => 'datetime',
    ];

    protected $fillable = [
        'movieID',
        'theaterID',
        'startTime',
        'endTime',
        'price', // cột trong DB là price chứ không phải basePrice
    ];

    public function orders()
    {
        // FK tại bảng orders: showtimeID => PK bảng showtimes: showtimeID
        return $this->hasMany(Order::class, 'showtimeID', 'showtimeID');
    }

    // Quan hệ: phim
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieID', 'movieID');
    }

    // Quan hệ: rạp
    public function theater()
    {
        return $this->belongsTo(MovieTheater::class, 'theaterID', 'theaterID');
    }

    // Quan hệ: vé
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'showtimeID', 'showtimeID');
    }

    //  Ghế đã đặt (qua bảng tickets)
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

    //  Ghế đang giữ tạm (SeatHold)
    public function holds()
    {
        return $this->hasMany(SeatHold::class, 'showtimeID', 'showtimeID');
    }

    //  Ghế còn trống
    public function availableSeats()
    {
        $reserved = $this->reservedSeats()->pluck('seats.seatID');
        $held = $this->holds()
            ->where('expires_at', '>', now())
            ->pluck('seatID');

        return Seat::where('theaterID', $this->theaterID)
            ->where('status', 'active')
            ->whereNotIn('seatID', $reserved)
            ->whereNotIn('seatID', $held)
            ->orderBy('verticalRow')
            ->orderBy('horizontalRow');
    }
}
