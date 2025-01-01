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

        if ($csvFile === false) {
            throw new \Exception("Could not open the CSV file.");
        }

        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstLine) {
                $contactLink = $data[13];
                $bandType = $data[8];  // Get the band_type field

                // Validate if the contact link is valid JSON
                if ($this->isValidJson($contactLink)) {
                    $contactLink = json_encode(json_decode($contactLink, true)); // Re-encode to ensure correct format
                } else {
                    $contactLink = null;
                }

                // Ensure band_type is a valid JSON array if it's not already
                if (!empty($bandType) && $bandType !== '[]') {
                    $bandType = json_encode([$bandType]); // Wrap in an array if needed
                } else {
                    $bandType = '[]'; // Set to an empty array if it's empty
                }

                // Create a new venue
                Venue::create([
                    "name" => $data[0],
                    "location" => $data[1],
                    "postal_town" => $data[2],
                    "longitude" => $data[3],
                    "latitude" => $data[4],
                    'w3w' => $data[5],
                    "capacity" => $data[6],
                    "in_house_gear" => $data[7],
                    "band_type" => $bandType,
                    "genre" => $data[9],
                    "contact_name" => $data[10],
                    "contact_number" => $data[11],
                    "contact_email" => $data[12],
                    "contact_link" => $contactLink,
                    "description" => $data[14],
                    "additional_info" => $data[15],
                    "logo_url" => $data[16],
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