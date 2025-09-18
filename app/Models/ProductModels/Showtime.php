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

    // Các ghế đã được mua (qua bảng tickets) trong suất này
    public function reservedSeats()
    {
        return $this->belongsToMany(
            Seat::class,          // model
            'tickets',            // pivot
            'showtimeID',         // FK trên pivot trỏ về showtimes
            'seatID',             // FK trên pivot trỏ về seats
            'showtimeID',         // local key
            'seatID'              // related key
        );
    }

    // (Nếu có bảng seat_holds để giữ chỗ tạm)
    public function holds()
    {
        return $this->hasMany(SeatHold::class, 'showtimeID', 'showtimeID');
    }

    // Lấy danh sách ghế còn trống (active, không bị giữ, không bị mua)
    public function availableSeats()
    {
        $reserved = $this->reservedSeats()->pluck('seats.seatID');
        $held = method_exists($this, 'holds')
            ? $this->holds()->where('expires_at', '>', now())->pluck('seatID')
            : collect([]);

        return Seat::where('theaterID', $this->theaterID)
            ->where('status', 'active')
            ->whereNotIn('seatID', $reserved)
            ->when($held->isNotEmpty(), fn($q) => $q->whereNotIn('seatID', $held))
            ->orderBy('verticalRow')->orderBy('horizontalRow');
    }
}
