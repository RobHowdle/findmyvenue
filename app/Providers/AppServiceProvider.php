<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Check if the application is running in console mode
        if ($this->app->runningInConsole()) {
            return; // Allow Artisan commands to run
        }

        // Check if the application is in maintenance mode
        if ($this->app->isDownForMaintenance()) {
            $clientIp = Request::ip();
            Log::info('Maintenance mode active for IP: ' . $clientIp);

            // Define your allowed IP address
            $allowedIp = '81.99.92.105';

            // Check if the request IP matches your allowed IP
            if ($clientIp !== $allowedIp) {
                // If not, throw an exception to show the maintenance page
                throw new ServiceUnavailableHttpException();
            } else {
                // If it matches, log the access granted
                Log::info('Access granted for IP: ' . $clientIp);
                return; // Allow access to the application
            }
        }
    }
}
