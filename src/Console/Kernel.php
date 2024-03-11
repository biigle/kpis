<?php

namespace Biigle\Modules\Kpis\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('kpis:count-unique-user')->monthlyOn(1, '0:0')->onOneServer();

        $schedule->command('kpis:determine-storage-usage')->monthlyOn(1, '0:0')->onOneServer();

        $schedule->command('kpis:count-user')->daily()->onOneServer();


        // Insert scheduled tasks here.
    }

    /**
     * Register the Closure based commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
