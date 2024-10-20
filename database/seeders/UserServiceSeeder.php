<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserServiceSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('first_name', 'Promoter')->firstOrFail();

        $serviceUserData = [
            'user_id' => $user->id,
            'serviceable_id' => 1,
            'serviceable_type' => 'App\Models\Promoter',
        ];

        DB::table('service_user')->updateOrInsert(
            [
                'user_id' => $user->id,
                'serviceable_id' => 1,
            ],
            $serviceUserData
        );
    }
}
