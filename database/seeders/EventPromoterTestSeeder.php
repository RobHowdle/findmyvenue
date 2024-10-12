<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventPromoterTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_promoter')->insert([
            [
                'event_id' => 1, // Summer Music Festival
                'promoter_id' => 1, // Example Promoter ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2, // Autumn Rock Concert
                'promoter_id' => 2, // Example Promoter ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 3, // Winter Jazz Night
                'promoter_id' => 3, // Example Promoter ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 4, // Spring Festival 2024
                'promoter_id' => 1, // Example Promoter ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 5, // Halloween Party 2024
                'promoter_id' => 2, // Example Promoter ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 6, // New Year Bash 2024
                'promoter_id' => 3, // Example Promoter ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
