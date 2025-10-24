<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class TicketPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $showtime;
    public $cinema;
    public $qrUrl;
    public $movieName;
    public $startTime;

    public function __construct($order)
    {
        $this->order = $order;
        $this->showtime = $order->showtime;
        $this->cinema = $this->showtime?->theater;
        $this->qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$order->order_code}";

        //  Lấy tên phim và định dạng thời gian đúng cột
        $this->movieName = optional($this->showtime?->movie)->title ?? 'Tên phim không rõ';
        $this->startTime = $this->showtime?->startTime
            ? Carbon::parse($this->showtime->startTime)->format('H:i d/m/Y')
            : 'Chưa rõ';
    }

    public function build()
    {
        return $this->subject("🎟 Vé xem phim #{$this->order->order_code}")
                    ->view('emails.ticket_paid')
                    ->with([
                        'order' => $this->order,
                        'showtime' => $this->showtime,
                        'cinema' => $this->cinema,
                        'qrUrl' => $this->qrUrl,
                        'movieName' => $this->movieName,
                        'startTime' => $this->startTime,
                    ]);
    }
}
