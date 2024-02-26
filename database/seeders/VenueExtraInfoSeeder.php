<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VenueExtraInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('venues_extra_info')->insert([
            'venues_id' => 1,
            'text' => 'Capacity: 200 (standing)
Gear: Full Stage and Lighting, In House PA, Sound Engineer, Lighting Engineer
Bands & Genres: The Forum caters for all types of bands such as originals, covers and tributes. They also cater for
many different genres from orchestral to pop, punk to rock and metal an many more.
Parking: Bands can drive into the rear car park which has an access door allowing for ease of moving gear on and off stage.
Green Room: The Forum also offers a green room for bands to store cases, bags and other items if required.
Cost: The Forum charge £XXX per show. This can either be paid in full or deducted from ticket sales.'
        ]);
    }
}