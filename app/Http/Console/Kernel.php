<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Yêu cầu Laravel chạy lệnh 'seats:release-expired' mỗi phút
        $schedule->command('seats:release-expired')->everyMinute();
    }

    // ...
}