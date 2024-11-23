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
        Permission::create(['name' => 'manage_videographer']);
        Permission::create(['name' => 'manage_designer']);
        Permission::create(['name' => 'manage_modules']);
        Permission::create(['name' => 'view_finances']);
        Permission::create(['name' => 'manage_finances']);
        Permission::create(['name' => 'view_events']);
        Permission::create(['name' => 'manage_events']);
        Permission::create(['name' => 'view_todo_list']);
        Permission::create(['name' => 'manage_todo_list']);
        Permission::create(['name' => 'view_reviews']);
        Permission::create(['name' => 'manage_reviews']);
        Permission::create(['name' => 'view_notes']);
        Permission::create(['name' => 'manage_notes']);
        Permission::create(['name' => 'view_documents']);
        Permission::create(['name' => 'manage_documents']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'view_jobs']);
        Permission::create(['name' => 'manage_jobs']);
    }
}
