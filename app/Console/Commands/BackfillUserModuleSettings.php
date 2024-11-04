<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserModuleSetting;
use Illuminate\Support\Facades\DB;

class BackfillUserModuleSettings extends Command
{
    protected $signature = 'backfill:user-module-settings';
    protected $description = 'Backfills user_module_settings records for existing users who lack them';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all users along with their serviceable_id and serviceable_type from service_user
        $usersWithServices = DB::table('users')
            ->leftJoin('service_user', 'users.id', '=', 'service_user.user_id')
            ->select('users.id as user_id', 'service_user.serviceable_id', 'service_user.serviceable_type')
            ->get();

        foreach ($usersWithServices as $userData) {
            // Check if the user already has module settings
            $existingSettings = UserModuleSetting::where('user_id', $userData->user_id)->exists();

            if (!$existingSettings) {
                // Start a transaction to ensure data integrity
                DB::transaction(function () use ($userData) {
                    $this->createDefaultModuleSettings($userData);
                });

                $this->info("Added module settings for user ID: {$userData->user_id}");
            } else {
                $this->info("Module settings already exist for user ID: {$userData->user_id}");
            }
        }

        $this->info("Backfill completed successfully.");
    }

    private function createDefaultModuleSettings($userData)
    {
        $defaultModules = [
            ['module_name' => 'events', 'is_enabled' => true],
            ['module_name' => 'todo_list', 'is_enabled' => true],
            ['module_name' => 'notes', 'is_enabled' => true],
            // Add additional modules as needed
        ];

        foreach ($defaultModules as $module) {
            UserModuleSetting::create([
                'user_id' => $userData->user_id,
                'module_name' => $module['module_name'],
                'is_enabled' => $module['is_enabled'],
                'serviceable_id' => $userData->serviceable_id,
                'serviceable_type' => $userData->serviceable_type,
            ]);
        }
    }
}