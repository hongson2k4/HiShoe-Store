<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Chạy lệnh orders:update-status hàng ngày vào lúc 00:00
        $schedule->command('orders:update-status')->daily(); // chỉ chạy 1 lần 1 ngày
    }
    //     protected function schedule(Schedule $schedule)
    // {
    //     $schedule->command('orders:update-status')->everyMinute(); // Chạy mỗi phút để test
    // }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}