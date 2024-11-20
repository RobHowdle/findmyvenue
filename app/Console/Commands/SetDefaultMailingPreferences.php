<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class SetDefaultMailingPreferences extends Command
{
    // The name and signature of the console command.
    protected $signature = 'users:set-default-mailing-preferences';

    // The console command description.
    protected $description = 'Set default mailing preferences for all users';

    // Execute the console command.
    public function handle()
    {
        ini_set('memory_limit', '-1');

        // Get the default mailing preferences from the config file
        $defaultPreferences = Config::get('mailing_preferences.communication_preferences');

        // Set all preferences to true (enabled)
        $preferences = [];
        foreach ($defaultPreferences as $preferenceKey => $preference) {
            $preferences[$preferenceKey] = true; // Default all preferences to true
        }

        // Get all users and update their preferences
        $users = User::all();

        foreach ($users as $user) {
            // Set the default preferences as JSON
            $user->mailing_preferences = json_encode($preferences);
            $user->save();

            // Output the result
            $this->info('Updated preferences for user: ' . $user->email);
        }

        $this->info('All users have been updated with default mailing preferences.');
    }
}
