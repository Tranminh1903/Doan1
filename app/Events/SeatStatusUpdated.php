<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $showtimeID;
    public $seats;
    public $status;

    public function __construct($showtimeID, $seats, $status)
    {
        $this->showtimeID = $showtimeID;
        $this->seats = $seats;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('showtime.' . $this->showtimeID);
    }

    public function broadcastAs()
    {
        return 'SeatStatusUpdated';
    }
}
