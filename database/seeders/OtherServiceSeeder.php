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
                OtherService::create([
                    "name" => $data[0],
                    "services" => $data[1],
                    "logo_url" => $data[2],
                    "location" => $data[3],
                    "postal_town" => $data[4],
                    "longitude" => $data[5],
                    "latitude" => $data[6],
                    "other_service_id" => $data[7],
                    "description" => $data[8],
                    "packages" => $data[9],
                    "environment_type" => $data[10],
                    "working_times" => $data[11],
                    "members" => $data[12],
                    "stream_urls" => $data[13],
                    "band_type" => $data[14],
                    "genre" => $data[15],
                    "contact_number" => $data[16],
                    "contact_email" => $data[17],
                    "contact_link" => $data[18],
                    "portfolio_link" => $data[19],
                ]);
            }
            $firstLine = false;
        }

        fclose($csvFile);
    }
}
