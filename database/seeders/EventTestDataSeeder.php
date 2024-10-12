<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Upcoming Events
        DB::table('events')->insert([
            [
                'name' => 'Summer Music Festival',
                'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2025-07-10 18:00:00'),
                'poster' => 'path/to/summer_music_festival_poster.jpg',
                'band_ids' => json_encode([1, 2]), // Example band IDs
                'attendance' => 0,
                'ticket_sales' => 0,
                'rating' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Autumn Rock Concert',
                'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2025-09-15 19:00:00'),
                'poster' => 'path/to/autumn_rock_concert_poster.jpg',
                'band_ids' => json_encode([3, 4]), // Example band IDs
                'attendance' => 0,
                'ticket_sales' => 0,
                'rating' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Winter Jazz Night',
                'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2025-12-20 20:00:00'),
                'poster' => 'path/to/winter_jazz_night_poster.jpg',
                'band_ids' => json_encode([5]), // Example band IDs
                'attendance' => 0,
                'ticket_sales' => 0,
                'rating' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Past Events
        DB::table('events')->insert([
            [
                'name' => 'Spring Festival 2024',
                'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2024-04-05 17:00:00'),
                'poster' => 'path/to/spring_festival_poster.jpg',
                'band_ids' => json_encode([1, 3]), // Example band IDs
                'attendance' => 150,
                'ticket_sales' => 120,
                'rating' => 85,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Halloween Party 2024',
                'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2024-10-31 21:00:00'),
                'poster' => 'path/to/halloween_party_poster.jpg',
                'band_ids' => json_encode([2, 4]), // Example band IDs
                'attendance' => 200,
                'ticket_sales' => 180,
                'rating' => 90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'New Year Bash 2024',
                'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2024-01-01 23:00:00'),
                'poster' => 'path/to/new_year_bash_poster.jpg',
                'band_ids' => json_encode([5]), // Example band IDs
                'attendance' => 300,
                'ticket_sales' => 250,
                'rating' => 95,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
