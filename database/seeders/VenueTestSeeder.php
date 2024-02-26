<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VenueTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('venues')->insert([
            'name' => 'The Forum Music Center',
            'location' => 'Darlington',
            'capacity' => '200',
            'in_house_gear' => 'Full Stage, PA, Lighting Rig',
            'band_type' => 'Any',
            'genre' => 'Any',
            'contact_name' => 'June',
            'contact_number' => '01325363135',
            'contact_email' => 'info@theforumonline.co.uk',
            'contact_link' => 'https://www.facebook.com/theforumonline, https://www.twitter.com/theforumonline, https://www.instagram.com/theforumonline'
        ]);
    }
}