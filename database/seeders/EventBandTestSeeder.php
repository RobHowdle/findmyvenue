<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventBandTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_band')->insert([
            [
                'event_id' => 1, // Summer Music Festival
                'band_id' => 1,  // Example Band ID from the OtherService table
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 1, // Summer Music Festival
                'band_id' => 2,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2, // Autumn Rock Concert
                'band_id' => 3,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 2, // Autumn Rock Concert
                'band_id' => 4,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 3, // Winter Jazz Night
                'band_id' => 5,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 4, // Spring Festival 2024
                'band_id' => 1,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 4, // Spring Festival 2024
                'band_id' => 3,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 5, // Halloween Party 2024
                'band_id' => 2,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 5, // Halloween Party 2024
                'band_id' => 4,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_id' => 6, // New Year Bash 2024
                'band_id' => 5,  // Example Band ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
