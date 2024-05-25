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
            'location' => 'Bradford, UK',
            'postal_town' => 'Bradford',
            'longitude' => '00.00',
            'latitude' => '00.00',
            'genre' => '[]',
            'band_types' => '[]',
            'logo_url' => '../../storage/app/public/images/shadow.jpg',
            'contact_number' => '01325123456',
            'contact_email' => 'shadowfestuk@gmail.com',
            'contact_link' => 'https://www.facebook.com/ShadowFest1',
            'about_me' => 'Lorem ipsum dolor sit amet consectetur. Odio ultricies morbi libero mauris. Justo lectus at hac nulla id elit habitasse blandit. Eget morbi massa enim risus lectus neque senectus turpis hendrerit. Ut congue mollis at a. Bibendum habitant adipiscing a sed tortor. Arcu vitae eget viverra viverra eu. Pharetra amet morbi suspendisse lorem cras cras potenti commodo ultricies. Ultricies mi commodo commodo fames et diam amet gravida. Egestas donec enim augue nibh non libero pulvinar. Amet eu netus etiam sapien sit purus sit. Condimentum quam quis sem morbi aliquam sed dignissim.

Enim ac urna bibendum lectus pellentesque sagittis imperdiet ultrices hac. Faucibus non lacus maecenas at consequat et augue nisi. Viverra etiam lorem vitae venenatis feugiat a vulputate vel ultrices. Faucibus odio volutpat accumsan enim. Aenean sodales ac sed mi metus nam bibendum id amet. Nibh nibh vel urna tortor id velit. Cras elementum id magna massa lacus vestibulum sodales tellus. Vitae enim porttitor pharetra eu enim ac cursus.',
            'my_venues' => 'Enim ac urna bibendum lectus pellentesque sagittis imperdiet ultrices hac. Faucibus non lacus maecenas at consequat et augue nisi. Viverra etiam lorem vitae venenatis feugiat a vulputate vel ultrices. Faucibus odio volutpat accumsan enim. Aenean sodales ac sed mi metus nam bibendum id amet. Nibh nibh vel urna tortor id velit. Cras elementum id magna massa lacus vestibulum sodales tellus. Vitae enim porttitor pharetra eu enim ac cursus.'
        ]);
    }
}
