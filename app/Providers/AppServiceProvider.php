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

        $cloudFlareIp = request()->header('CF-Connecting-IP') ?: request()->ip();

        // Check if the application is in maintenance mode
        if ($this->app->isDownForMaintenance()) {
            $clientIp = Request::ip();
            Log::info('Maintenance mode active for IP: ' . $clientIp);
            Log::info('Clouflare IP: ' . $cloudFlareIp);

            // Define your allowed IP address
            $allowedIp = '81.99.92.105';

            // Check if request has the Laravel secret bypass cookie for maintenance mode
            if (Request::hasCookie('laravel_maintenance')) {
                Log::info('Access granted via secret key for IP: ' . $clientIp);
                return; // Allow access for secret key
            }

            // Check if the request IP matches the allowed IP
            if ($clientIp === $allowedIp) {
                Log::info('Access granted for IP: ' . $clientIp);
                return; // Allow access to the application
            }

            // If neither the secret nor the IP is valid, show the maintenance page
            Log::info('Access denied for IP: ' . $clientIp);
            throw new ServiceUnavailableHttpException();
        }
    }
}
