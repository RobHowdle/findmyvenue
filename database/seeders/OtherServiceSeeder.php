<?php

namespace Database\Seeders;

use App\Models\OtherService;
use Illuminate\Database\Seeder;

class OtherServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/other_services.csv"), "r");

        if ($csvFile === false) {
            throw new \Exception("Could not open the CSV file.");
        }

        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstLine) {
                $contactLink = $data[18];

                // Validate if the contact link is valid JSON
                if ($this->isValidJson($contactLink)) {
                    // Decode JSON and re-encode it as a JSON string to ensure it's in correct format
                    $contactLink = json_encode(json_decode($contactLink, true));
                } else {
                    $contactLink = null; // Set to null if it's invalid JSON
                }
                OtherService::create([
                    "name" => $data[0],
                    "logo_url" => $data[1],
                    "location" => $data[2],
                    "postal_town" => $data[3],
                    "longitude" => $data[4],
                    "latitude" => $data[5],
                    "other_service_id" => $data[6],
                    "description" => $data[7],
                    "packages" => $data[8],
                    "environment_type" => $data[9],
                    "working_times" => $data[10],
                    "members" => $data[11],
                    "stream_urls" => $data[12],
                    "band_type" => $data[13],
                    "genre" => $data[14],
                    "contact_name" => $data[15],
                    "contact_number" => $data[16],
                    "contact_email" => $data[17],
                    "contact_link" => $contactLink,
                    "portfolio_link" => $data[19],
                    "services" => $data[20],
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
