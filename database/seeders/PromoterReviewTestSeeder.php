<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromoterReview;
use Faker\Factory as Faker;

class PromoterReviewTestSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Generate 20 reviews
        for ($i = 0; $i < 20; $i++) {
            PromoterReview::create([
                'promoter_id' => 32,
                'communication_rating' => $faker->numberBetween(1, 5),
                'rop_rating' => $faker->numberBetween(1, 5), // ROP Rating
                'promotion_rating' => $faker->numberBetween(1, 5),
                'quality_rating' => $faker->numberBetween(1, 5),
                'review' => $faker->paragraph($faker->numberBetween(1, 5)), // Random short or long review
                'author' => $faker->randomElement([$faker->name, 'Anonymous']), // Random name or 'Anonymous'
                'review_approved' => 0,
                'display' => 0,
                'reviewer_ip' => $faker->ipv4, // Random IP address
            ]);
        }
    }
}
