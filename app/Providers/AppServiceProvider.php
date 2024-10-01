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
        // Allow Artisan commands to run
        if ($this->app->runningInConsole()) {
            return;
        }

        // Check if the application is in maintenance mode
        if ($this->app->isDownForMaintenance()) {
            $ipAddress = Request::ip();
            Log::info('Maintenance mode active for IP: ' . $ipAddress);

            // List of allowed IPs
            $allowedIps = ['81.99.92.105']; // Your IP Address

            // Log the allowed IPs for debugging
            Log::info('Allowed IPs: ' . implode(', ', $allowedIps));

            // If the IP is not allowed, throw an exception
            if (!in_array($ipAddress, $allowedIps)) {
                Log::warning('Access denied for IP: ' . $ipAddress);
                throw new ServiceUnavailableHttpException();
            } else {
                Log::info('Access granted for IP: ' . $ipAddress);
            }
        } else {
            Log::info('Not Active! Problem!');
        }
    }
}
