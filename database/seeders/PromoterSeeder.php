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
                // Check if contact_link contains valid JSON
                $contactLink = $data[13];
                if ($this->isValidJson($contactLink)) {
                    // Decode JSON and re-encode it as a JSON string
                    $contactLink = json_encode(json_decode($contactLink, true));
                } else {
                    $contactLink = null; // Set to null if invalid JSON
                }

                Promoter::create([
                    "name" => $data[0],
                    "location" => $data[1],
                    "postal_town" => $data[2],
                    "latitude" => $data[3],
                    "longitude" => $data[4],
                    "logo_url" => $data[5],
                    "description" => $data[6],
                    "my_venues" => $data[7],
                    "genre" => $data[8],
                    "band_type" => $data[9],
                    "contact_name" => $data[10],
                    "contact_number" => $data[11],
                    "contact_email" => $data[12],
                    "contact_link" => $contactLink,
                ]);
            }
            $firstLine = false;
        }
        fclose($csvFile);
    }

    /**
     * Check if a string is a valid JSON.
     *
     * @param string $string
     * @return bool
     */
    private function isValidJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
