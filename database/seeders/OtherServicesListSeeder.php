<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OtherServicesListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('other_services_list')->insert([
            [
                'service_name' => 'Photography',
                'image_url' => asset('storage/images/system/photography.jpg'),
            ],
            [
                'service_name' => 'Videography',
                'image_url' => asset('storage/images/system/videography.jpg'),
            ],
            [
                'service_name' => 'Designer',
                'image_url' => asset('storage/images/system/designer.jpg'),
            ],
            [
                'service_name' => 'Artist',
                'image_url' => asset('storage/images/system/band.jpg'),
            ],
        ]);
    }
}
