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
                $packages = $data[8] ?? null;
                $environmentType = $data[9] ?? null;
                $workingTimes = $data[10] ?? null;
                $members = $data[11] ?? null;
                $streamUrls = $data[12] ?? null;
                $bandType = $data[13] ?? null;
                $genre = $data[14] ?? null;
                $contactLink = $data[18] ?? null;
                $portfolioImages = $data[20] ?? null;

                // Validate if the contact link is valid JSON
                if ($this->isValidJson($contactLink)) {
                    $contactLink = json_encode(json_decode($contactLink, true)); // Re-encode to ensure correct format
                } else {
                    $contactLink = null;
                }

                if (!empty($packages) && $packages !== '[]') {
                    $packages = json_encode([$packages]); // Wrap in an array if needed
                } else {
                    $packages = '[]'; // Set to an empty array if it's empty
                }

                if (!empty($environmentType) && $environmentType !== '[]') {
                    $environmentType = json_encode([$environmentType]); // Wrap in an array if needed
                } else {
                    $environmentType = '[]'; // Set to an empty array if it's empty
                }
                if (!empty($workingTimes) && $workingTimes !== '[]') {
                    $workingTimes = json_encode([$workingTimes]); // Wrap in an array if needed
                } else {
                    $workingTimes = '[]'; // Set to an empty array if it's empty
                }
                if (!empty($members) && $members !== '[]') {
                    $members = json_encode([$members]); // Wrap in an array if needed
                } else {
                    $members = '[]'; // Set to an empty array if it's empty
                }
                if (!empty($streamUrls) && $streamUrls !== '[]') {
                    $streamUrls = json_encode([$streamUrls]); // Wrap in an array if needed
                } else {
                    $streamUrls = '[]'; // Set to an empty array if it's empty
                }
                if (!empty($genre) && $genre !== '[]') {
                    $genre = json_encode([$genre]); // Wrap in an array if needed
                } else {
                    $genre = '[]'; // Set to an empty array if it's empty
                }

                // Ensure band_type is a valid JSON array if it's not already
                if (!empty($bandType) && $bandType !== '[]') {
                    $bandType = json_encode([$bandType]); // Wrap in an array if needed
                } else {
                    $bandType = '[]'; // Set to an empty array if it's empty
                }
                if (!empty($portfolioImages) && $portfolioImages !== '[]') {
                    $portfolioImages = json_encode([$portfolioImages]); // Wrap in an array if needed
                } else {
                    $portfolioImages = '[]'; // Set to an empty array if it's empty
                }

                // Create a new venue
                OtherService::create([
                    "name" => $data[0],
                    "logo_url" => $data[1],
                    "location" => $data[2],
                    "postal_town" => $data[3],
                    "longitude" => $data[4],
                    "latitude" => $data[5],
                    "other_service_id" => $data[6],
                    "description" => $data[7],
                    "packages" => $packages,
                    "environment_type" => $environmentType,
                    "working_times" => $workingTimes,
                    "members" => $members,
                    "stream_urls" => $streamUrls,
                    "band_type" => $bandType,
                    "genre" => $genre,
                    "contact_name" => $data[15],
                    "contact_number" => $data[16],
                    "contact_email" => $data[17],
                    "contact_link" => $contactLink,
                    "portfolio_link" => $data[19],
                    "portfolio_images" => $portfolioImages,
                    "services" => $data[21],
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