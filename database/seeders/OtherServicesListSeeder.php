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
        DB::table('other_services_list')->insert(
            [
                'service_name' => 'Photography',
            ],
            [
                'service_name' => 'Videography',
            ],
            [
                'service_name' => 'Designer',
            ],
            [
                'service_name' => 'Band',
            ]
        );
    }
}
