<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PromoterTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promoters')->insert([
            'name' => 'Shadow Fest Promotions',
            'location' => 'Bradford',
            'contact_number' => '01325123456',
            'contact_email' => 'shadowfestuk@gmail.com',
            'contact_link' => 'https://www.facebook.com/ShadowFest1',
        ]);
    }
}