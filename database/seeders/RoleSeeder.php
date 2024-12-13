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
        Role::create(['name' => 'videographer']);
        Role::create(['name' => 'designer']);
        Role::create(['name' => 'administrator']);

        // Retrieve permissions if they exist
        $manageVenuePermission = Permission::where('name', 'manage_venue')->first();
        $managePromotionPermission = Permission::where('name', 'manage_promoter')->first();
        $manageBandPermission = Permission::where('name', 'manage_band')->first();
        $managePhotographerPermission = Permission::where('name', 'manage_photographer')->first();
        $manageVideographerPermission = Permission::where('name', 'manage_videographer')->first();
        $manageDesignerPermission = Permission::where('name', 'manage_designer')->first();
        $manageStandardUserPermission = Permission::where('name', 'manage_standard_user')->first();
        $manageModulesPermission = Permission::where('name', 'manage_modules')->first();
        $viewFinancePermission = Permission::where('name', 'view_finances')->first();
        $manageFinancePermission = Permission::where('name', 'manage_finances')->first();
        $viewEventsPermission = Permission::where('name', 'view_events')->first();
        $manageEventsPermission = Permission::where('name', 'manage_events')->first();
        $viewTodoListPermission = Permission::where('name', 'view_todo_list')->first();
        $manageTodoListPermission = Permission::where('name', 'manage_todo_list')->first();
        $viewReviewsPermission = Permission::where('name', 'view_reviews')->first();
        $manageReviewsPermission = Permission::where('name', 'manage_reviews')->first();
        $viewNotesPermission = Permission::where('name', 'view_notes')->first();
        $manageNotesPermission = Permission::where('name', 'manage_notes')->first();
        $viewDocumentsPermission = Permission::where('name', 'view_documents')->first();
        $manageDocumentsPermission = Permission::where('name', 'manage_documents')->first();
        $viewUsersPermission = Permission::where('name', 'view_users')->first();
        $manageUsersPermission = Permission::where('name', 'manage_users')->first();
        $viewJobsPermission = Permission::where('name', 'view_jobs')->first();
        $manageJobsPermission = Permission::where('name', 'manage_jobs')->first();

        // Assign permissions to roles if they exist
        if ($manageVenuePermission) {
            Role::where('name', 'venue')->first()->syncPermissions([
                $manageVenuePermission,
                $manageModulesPermission,
                $viewFinancePermission,
                $manageFinancePermission,
                $viewEventsPermission,
                $manageEventsPermission,
                $viewTodoListPermission,
                $manageTodoListPermission,
                $viewReviewsPermission,
                $manageReviewsPermission,
                $viewNotesPermission,
                $manageNotesPermission,
                $viewDocumentsPermission,
                $manageDocumentsPermission,
                $viewUsersPermission,
                $manageUsersPermission,
                $viewJobsPermission,
                $manageJobsPermission
            ]);
        }
        if ($managePromotionPermission) {
            Role::where('name', 'promoter')->first()->syncPermissions([
                $managePromotionPermission,
                $manageModulesPermission,
                $viewFinancePermission,
                $manageFinancePermission,
                $viewEventsPermission,
                $manageEventsPermission,
                $viewTodoListPermission,
                $manageTodoListPermission,
                $viewReviewsPermission,
                $manageReviewsPermission,
                $viewNotesPermission,
                $manageNotesPermission,
                $viewDocumentsPermission,
                $manageDocumentsPermission,
                $viewUsersPermission,
                $manageUsersPermission,
                $viewJobsPermission,
                $manageJobsPermission
            ]);
        }
        if ($manageBandPermission) {
            Role::where('name', 'band')->first()->syncPermissions([
                $manageBandPermission,
                $manageModulesPermission,
                $viewFinancePermission,
                $manageFinancePermission,
                $viewEventsPermission,
                $manageEventsPermission,
                $viewTodoListPermission,
                $manageTodoListPermission,
                $viewReviewsPermission,
                $manageReviewsPermission,
                $viewNotesPermission,
                $manageNotesPermission,
                $viewDocumentsPermission,
                $manageDocumentsPermission,
                $viewUsersPermission,
                $manageUsersPermission,
                $viewJobsPermission,
                $manageJobsPermission
            ]);
        }
        if ($managePhotographerPermission) {
            Role::where('name', 'photographer')->first()->syncPermissions([
                $managePhotographerPermission,
                $manageModulesPermission,
                $viewFinancePermission,
                $manageFinancePermission,
                $viewEventsPermission,
                $manageEventsPermission,
                $viewTodoListPermission,
                $manageTodoListPermission,
                $viewReviewsPermission,
                $manageReviewsPermission,
                $viewNotesPermission,
                $manageNotesPermission,
                $viewDocumentsPermission,
                $manageDocumentsPermission,
                $viewUsersPermission,
                $manageUsersPermission,
                $viewJobsPermission,
                $manageJobsPermission
            ]);
        }
        if ($manageVideographerPermission) {
            Role::where('name', 'videographer')->first()->syncPermissions([
                $manageVideographerPermission,
                $manageModulesPermission,
                $viewFinancePermission,
                $manageFinancePermission,
                $viewEventsPermission,
                $manageEventsPermission,
                $viewTodoListPermission,
                $manageTodoListPermission,
                $viewReviewsPermission,
                $manageReviewsPermission,
                $viewNotesPermission,
                $manageNotesPermission,
                $viewDocumentsPermission,
                $manageDocumentsPermission,
                $viewUsersPermission,
                $manageUsersPermission,
                $viewJobsPermission,
                $manageJobsPermission
            ]);
        }
        if ($manageDesignerPermission) {
            Role::where('name', 'designer')->first()->syncPermissions([
                $manageDesignerPermission,
                $manageModulesPermission,
                $viewFinancePermission,
                $manageFinancePermission,
                $viewEventsPermission,
                $manageEventsPermission,
                $viewTodoListPermission,
                $manageTodoListPermission,
                $viewReviewsPermission,
                $manageReviewsPermission,
                $viewNotesPermission,
                $manageNotesPermission,
                $viewDocumentsPermission,
                $manageDocumentsPermission,
                $viewUsersPermission,
                $manageUsersPermission,
                $viewJobsPermission,
                $manageJobsPermission
            ]);
        }
        if ($manageStandardUserPermission) {
            Role::where('name', 'standard')->first()->syncPermissions([
                $manageStandardUserPermission,
                $manageModulesPermission,
                $viewEventsPermission,
            ]);
        }

        $user = User::find(1);
        $user->assignRole('administrator');
    }
}
