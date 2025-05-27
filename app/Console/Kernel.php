<?php

namespace App\Console;

use App\Jobs\NotifyBranchExpired;
use App\Jobs\NotifyBranchBeforeExpire;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule)
    // {
    //     // $schedule->job(new NotifyBranchBeforeExpire)->daily();
    //     // $schedule->job(new NotifyBranchExpired)->daily();

    // }
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notify:branch-owners')->daily();
        $schedule->command('branches:unpublish')->daily();
        $schedule->command('logs:clear')->dailyAt('00:00');


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
