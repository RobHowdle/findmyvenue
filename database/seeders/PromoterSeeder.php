<?php

namespace Database\Seeders;

use App\Models\Promoter;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PromoterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/promoters.csv"), "r");
        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstLine) {
                Promoter::create([
                    "name" => $data['0'],
                    "location" => $data['1'],
                    "postal_town" => $data['2'],
                    "latitude" => $data['3'],
                    "longitude" => $data['4'],
                    "logo_url" => $data['5'],
                    "about_me" => $data['6'],
                    "my_venues" => $data['7'],
                    "genre" => $data['8'],
                    "band_types" => $data['9'],
                    "contact_name" => $data['10'],
                    "contact_number" => $data['11'],
                    "contact_email" => $data['12'],
                    "contact_link" => $data['13'],
                ]);
            }
            $firstLine = false;
        }
        fclose($csvFile);
    }
}
