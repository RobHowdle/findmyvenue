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
                $contactLink = $data[12];

                // Validate if the contact link is valid JSON
                if ($this->isValidJson($contactLink)) {
                    // Decode JSON and re-encode it as a JSON string to ensure it's in correct format
                    $contactLink = json_encode(json_decode($contactLink, true));
                } else {
                    $contactLink = null; // Set to null if it's invalid JSON
                }

                // Create a new venue
                Venue::create([
                    "name" => $data[0],
                    "location" => $data[1],
                    "postal_town" => $data[2],
                    "longitude" => $data[3],
                    "latitude" => $data[4],
                    "capacity" => $data[5],
                    "in_house_gear" => $data[6],
                    "band_type" => $data[7],
                    "genre" => $data[8],
                    "contact_name" => $data[9],
                    "contact_number" => $data[10],
                    "contact_email" => $data[11],
                    "contact_link" => $contactLink, // Use the validated and possibly re-encoded $contactLink
                    "description" => $data[13],
                    "additional_info" => $data[14],
                    // "logo_url" => $data[15],
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
