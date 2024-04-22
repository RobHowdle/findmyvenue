<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'standard']);
        Role::create(['name' => 'venue']);
        Role::create(['name' => 'promoter']);
        Role::create(['name' => 'band']);
        Role::create(['name' => 'photographer']);
        Role::create(['name' => 'designer']);
        Role::create(['name' => 'administrator']);

        // Retrieve permissions if they exist
        $manageVenuePermission = Permission::where('name', 'manage_venue')->first();
        $managePromotionPermission = Permission::where('name', 'manage_promoter')->first();
        $manageBandPermission = Permission::where('name', 'manage_band')->first();
        $managePhotographerPermission = Permission::where('name', 'manage_photographer')->first();
        $manageDesignerPermission = Permission::where('name', 'manage_designer')->first();

        // Assign permissions to roles if they exist
        if ($manageVenuePermission) {
            Role::where('name', 'venue')->first()->syncPermissions([$manageVenuePermission]);
        }
        if ($managePromotionPermission) {
            Role::where('name', 'promoter')->first()->syncPermissions([$managePromotionPermission]);
        }
        if ($manageBandPermission) {
            Role::where('name', 'band')->first()->syncPermissions([$manageBandPermission]);
        }
        if ($managePhotographerPermission) {
            Role::where('name', 'photographer')->first()->syncPermissions([$managePhotographerPermission]);
        }
        if ($manageDesignerPermission) {
            Role::where('name', 'designer')->first()->syncPermissions([$manageDesignerPermission]);
        }

        $user = User::find(1);
        $user->assignRole('administrator');
    }
}
