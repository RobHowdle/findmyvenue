<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'manage_venue']);
        Permission::create(['name' => 'manage_promoter']);
        Permission::create(['name' => 'manage_band']);
        Permission::create(['name' => 'manage_photographer']);
        Permission::create(['name' => 'manage_designer']);
    }
}
