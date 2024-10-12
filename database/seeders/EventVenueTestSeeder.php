<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventVenueTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_venue')->insert([
            [
                'event_id' => 1, // Summer Music Festival
                'venue_id' => 1, // Example Venue ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2, // Autumn Rock Concert
                'venue_id' => 2, // Example Venue ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 3, // Winter Jazz Night
                'venue_id' => 3, // Example Venue ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 4, // Spring Festival 2024
                'venue_id' => 4, // Example Venue ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 5, // Halloween Party 2024
                'venue_id' => 5, // Example Venue ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 6, // New Year Bash 2024
                'venue_id' => 6, // Example Venue ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
