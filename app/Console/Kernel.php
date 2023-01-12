<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // schedule->command('activitylog:clean')->quarterly();
        // $schedule->command('telescope:prune --hours=48')->daily();
        // $schedule->command('sanctum:prune-expired --hours=24')->daily();
        // $schedule->command('inspire')->hourly();
        // $schedule->command('auth:clear-resets')->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
