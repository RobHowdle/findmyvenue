<?php

namespace App\Console;

use App\Console\Commands\SetDefaultMailingPreferences; // Add the correct namespace
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load all commands in the Commands directory
        $this->load(__DIR__ . '/Commands');

        // Ensure you register the custom command here
        $this->commands([
            SetDefaultMailingPreferences::class,
        ]);

        // Optionally, you can keep this if you have other commands to load from routes/console.php
        require base_path('routes/console.php');
    }
}
