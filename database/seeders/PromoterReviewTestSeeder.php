<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PromoterReviewTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promoter_reviews')->insert([
            'promoter_id' => 1,
            'review' => 'Jamie and Shadowfest have been really good to all my bands that have played there  joy to play for.',
            'author' => 'Alex Grey',
            'display' => 1,
        ]);
    }
}