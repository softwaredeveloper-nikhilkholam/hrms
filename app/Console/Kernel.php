<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateAttendanceLogs::class, // update Attendances
        Commands\UpdateHolidays::class, // update Holiday
        Commands\UpdateAGFLast7Days::class, // update UpdateAGFLast7Days
        Commands\UpdateMismatchTime::class, // update UpdateMismatchTime
        Commands\CheckHalfDayOrAbsentDay::class, // update UpdateMismatchTime
      
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('command:updateAttendanceLogs')->everyFiveMinutes(); 
        // $schedule->command('command:UpdateHolidays')->monthlyOn(01, '00:04');    
        // $schedule->command('command:UpdateAGFLast7Days')->dailyAt('02:03');    
        // $schedule->command('command:UpdateMismatchTime')->dailyAt('03:03');    
        // $schedule->command('command:CheckHalfDayOrAbsentDay')->dailyAt('01:03');    
        // $schedule->command('command:CheckHalfDayOrAbsentDay')->dailyAt('01:37');    
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
