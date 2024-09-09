<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OtherServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $photographerId = DB::table('other_services_list')->where('service_name', 'Photography')->value('id');
        $videographerId = DB::table('other_services_list')->where('service_name', 'Videography')->value('id');
        $bandId = DB::table('other_services_list')->where('service_name', 'Band')->value('id');
        $designerId = DB::table('other_services_list')->where('service_name', 'Designer')->value('id');
        DB::table('other_services')->insert([
            [
                'name' => 'Howdle Photography',
                'logo_url' => '',
                'location' => 'Darlington, UK',
                'postal_town' => 'Darlington',
                'longitude' => '00.00',
                'latitude' => '00.00',
                'other_service_id' => $photographerId,
                'description' => 'This is a test description',
                'packages' => '[]',
                'environment_type' => '[]',
                'working_times' => '[]',
                'contact_number' => '07305988990',
                'contact_email' => 'rob@mail.com',
                'contact_link' => 'https://www.facebook.com',
                'services' => 'Photography'
            ],
            [
                'name' => 'Howdle 2 Photography',
                'logo_url' => '',
                'location' => 'Darlington, UK',
                'postal_town' => 'Darlington',
                'longitude' => '00.00',
                'latitude' => '00.00',
                'other_service_id' => $photographerId,
                'description' => 'This is a test description',
                'packages' => '[]',
                'environment_type' => '[]',
                'working_times' => '[]',
                'contact_number' => '07305988990',
                'contact_email' => 'rob@mail.com',
                'contact_link' => 'https://www.facebook.com',
                'services' => 'Photography'
            ],
            [
                'name' => 'Fateweaver',
                'logo_url' => '',
                'location' => 'Darlington, UK',
                'postal_town' => 'Darlington',
                'longitude' => '00.00',
                'latitude' => '00.00',
                'other_service_id' => $bandId,
                'description' => 'This is a test description',
                'packages' => '[]',
                'environment_type' => '[]',
                'working_times' => '[]',
                'contact_number' => '07305988990',
                'contact_email' => 'rob@mail.com',
                'contact_link' => 'https://www.facebook.com',
                'services' => 'Band'
            ],
            [
                'name' => 'RobCamProductions',
                'logo_url' => '',
                'location' => 'Darlington, UK',
                'postal_town' => 'Darlington',
                'longitude' => '00.00',
                'latitude' => '00.00',
                'other_service_id' => $videographerId,
                'description' => 'This is a test description',
                'packages' => '[]',
                'environment_type' => '[]',
                'working_times' => '[]',
                'contact_number' => '07305988990',
                'contact_email' => 'rob@mail.com',
                'contact_link' => 'https://www.facebook.com',
                'services' => 'Videography'
            ],
            [
                'name' => 'Dilluzional Illustrations',
                'logo_url' => '',
                'location' => 'Darlington, UK',
                'postal_town' => 'Darlington',
                'longitude' => '00.00',
                'latitude' => '00.00',
                'other_service_id' => $designerId,
                'description' => 'This is a test description',
                'packages' => '[]',
                'environment_type' => '[]',
                'working_times' => '[]',
                'contact_number' => '07305988990',
                'contact_email' => 'rob@mail.com',
                'contact_link' => 'https://www.facebook.com',
                'services' => 'Designer'
            ],
        ]);
    }
}
