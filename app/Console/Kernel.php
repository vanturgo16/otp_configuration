<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $now=Carbon::now()->format('YmdHis');;
        $schedule->command('app:recap-stock-s-o-m-cron')
            ->monthlyOn(1, '00:00')
            ->sendOutputTo("storage/logs/SOMRecap_".$now.".txt");
        
        $schedule->command('app:recap-stock-e-o-m-cron')
            ->lastDayOfMonth('23:00')	
            ->sendOutputTo("storage/logs/EOMRecap_".$now.".txt");
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
