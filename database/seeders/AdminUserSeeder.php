<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Rob',
            'email' => 'robhowdle94@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('yfT11LpReC43(dV(U5'),
        ]);

        DB::table('users')->insert([
            'name' => 'Promoter',
            'email' => 'robhowdle94@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('yfT11LpReC43(dV(U5'),
        ]);
    }
}
