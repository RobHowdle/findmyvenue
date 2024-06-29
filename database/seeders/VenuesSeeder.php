<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VenuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/venues.csv"), "r");
        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstLine) {
                Venue::create([
                    "name" => $data['0'],
                    "location" => $data['1'],
                    "postal_town" => $data['2'],
                    "longitude" => $data['3'],
                    "latitude" => $data['4'],
                    "capacity" => $data['5'],
                    "in_house_gear" => $data['6'],
                    "band_type" => $data['7'],
                    "genre" => $data['8'],
                    "contact_name" => $data['9'],
                    "contact_number" => $data['10'],
                    "contact_email" => $data['11'],
                    "contact_link" => $data['12'],
                    "description" => $data['13'],
                    "additional_info" => $data['14'],
                    "logo_url" => $data['15'],
                ]);
            }
            $firstLine = false;
        }
        fclose($csvFile);
    }
}
