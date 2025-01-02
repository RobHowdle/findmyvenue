<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OtherService; // Replace with the correct model namespace
use Illuminate\Support\Facades\Storage;

class UpdateOtherServicesFromCSV extends Command
{
    protected $signature = 'update:other-services-from-csv {file}';
    protected $description = 'Update other services from a CSV file';

    public function handle()
    {
        // Get the CSV file path from the command argument
        $filePath = base_path('database/data/' . $this->argument('file'));

        // Check if the file exists
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        // Open the CSV file
        $file = fopen($filePath, 'r');

        // Read the CSV header row
        $headers = fgetcsv($file);

        // Loop through the CSV rows
        while (($row = fgetcsv($file)) !== false) {
            // Map the CSV row to an associative array
            $data = array_combine($headers, $row);

            // Find the existing record in the database
            $service = OtherService::where('name', $data['name'])->first();

            if ($service) {
                // Update the record with new data
                $service->update([
                    'logo_url' => $data['logo_url'],
                    'location' => $data['location'],
                    'postal_town' => $data['postal_town'],
                    'longitude' => $data['longitude'],
                    'latitude' => $data['latitude'],
                    'other_service_id' => $data['other_service_id'],
                    'description' => $data['description'],
                    'packages' => $data['packages'], // Make sure this is valid JSON
                    'environment_type' => $data['environment_type'], // Valid JSON
                    'working_times' => $data['working_times'], // Valid JSON
                    'members' => $data['members'], // Valid JSON
                    'stream_urls' => $data['stream_urls'], // Valid JSON
                    'band_type' => $data['band_type'],
                    'genre' => $data['genre'],
                    'contact_name' => $data['contact_name'],
                    'contact_number' => $data['contact_number'],
                    'contact_email' => $data['contact_email'],
                    'contact_link' => $data['contact_link'], // Valid JSON
                    'portfolio_link' => $data['portfolio_link'],
                    'portfolio_images' => $data['portfolio_images'], // Valid JSON
                    'services' => $data['services'], // Valid JSON
                ]);

                $this->info("Updated: {$data['name']}");
            } else {
                $this->warn("Service not found: {$data['name']}");
            }
        }

        // Close the file
        fclose($file);

        $this->info('Update completed!');
        return 0;
    }
}