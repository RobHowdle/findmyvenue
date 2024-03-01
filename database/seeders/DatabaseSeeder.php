<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\VenueTestSeeder;
use Database\Seeders\PromoterTestSeeder;
use Database\Seeders\VenueExtraInfoSeeder;
use Database\Seeders\PromoterReviewTestSeeder;
use Database\Seeders\PromoterVenueTestPivotSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            // VenueTestSeeder::class,
            // VenueExtraInfoSeeder::class,
            // PromoterTestSeeder::class,
            // PromoterVenueTestPivotSeeder::class,
            // PromoterReviewTestSeeder::class,
        ]);
    }
}