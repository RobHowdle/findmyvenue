<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\VenuesSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\VenueTestSeeder;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\UserServiceSeeder;
use Database\Seeders\OtherServiceSeeder;
use Database\Seeders\PromoterTestSeeder;
use Database\Seeders\TodoTestDataSeeder;
use Database\Seeders\VenueExtraInfoSeeder;
use Database\Seeders\FinanceTestDataSeeder;
use Database\Seeders\OtherServicesListSeeder;
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
            PermissionsSeeder::class,
            // VenueTestSeeder::class, // For Testing Data
            VenuesSeeder::class,
            VenueExtraInfoSeeder::class, // For Testing Data
            // PromoterTestSeeder::class,
            PromoterSeeder::class,
            PromoterVenueTestPivotSeeder::class,
            PromoterReviewTestSeeder::class,
            RoleSeeder::class,
            OtherServicesListSeeder::class,
            OtherServiceSeeder::class,
            FinanceTestDataSeeder::class,
            UserServiceSeeder::class,
            TodoTestDataSeeder::class,
        ]);
    }
}
