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

            // Define the main domain to allow access
            $allowedDomain = 'yournextshow.co.uk';
            // Define your allowed IP address
            $allowedIp = '81.99.92.105';

            // Check if the request domain matches the allowed domain or the request IP matches
            if (Request::getHost() === $allowedDomain || $clientIp === $allowedIp) {
                Log::info('Access granted for IP: ' . $clientIp . ' or domain: ' . $allowedDomain);
                return; // Allow access to the application
            } else {
                // If not, throw an exception to show the maintenance page
                Log::info('Access denied for IP - PROBLEM!: ' . $clientIp);
                throw new ServiceUnavailableHttpException();
            }
        }
    }
}
