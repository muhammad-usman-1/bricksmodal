<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class ExpandedRolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create additional roles
        $roles = [
            ['id' => 4, 'title' => 'Manager'],
            ['id' => 5, 'title' => 'Production Manager'],
            ['id' => 6, 'title' => 'Editor'],
            ['id' => 7, 'title' => 'Content Creator'],
            ['id' => 8, 'title' => 'Finance Manager'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['id' => $role['id']], ['title' => $role['title']]);
        }

        // Create granular permissions
        $permissions = [
            // Dashboard access
            'dashboard_access',

            // Main Permission Categories (for admin forms)
            'talent_management',
            'project_management', 
            'payment_management',

            // Project/Casting management
            'projects_view',
            'projects_create',
            'projects_edit',
            'projects_delete',
            'projects_approve',

            // Talent management
            'talents_view',
            'talents_create',
            'talents_edit',
            'talents_delete',
            'talents_approve',
            'talents_reject',

            // Payment management
            'payments_view',
            'payments_create',
            'payments_edit',
            'payments_delete',
            'payments_approve',
            'payments_process',

            // Admin management
            'admins_view',
            'admins_create',
            'admins_edit',
            'admins_delete',

            // Settings management
            'settings_view',
            'settings_edit',

            // Reports and analytics
            'reports_view',
            'reports_create',

            // Content management
            'content_view',
            'content_create',
            'content_edit',
            'content_delete',

            // Communication
            'notifications_send',
            'notifications_view',

            // System administration
            'system_backup',
            'system_maintenance',

            // Existing permissions (to maintain compatibility)
            'user_management_access',
            'super_admin_access'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['title' => $permission]);
        }

        // Assign permissions to roles
        $this->assignRolePermissions();
    }

    private function assignRolePermissions()
    {
        // Super Admin - All permissions
        $superAdminRole = Role::where('title', 'Super Admin')->first();
        if ($superAdminRole) {
            $allPermissions = Permission::all();
            $superAdminRole->permissions()->sync($allPermissions);
        }

        // Manager - Project and talent management
        $managerRole = Role::where('title', 'Manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::whereIn('title', [
                'dashboard_access',
                'projects_view',
                'projects_create',
                'projects_edit',
                'talents_view',
                'talents_approve',
                'talents_reject',
                'payments_view',
                'reports_view',
                'notifications_view',
                'notifications_send'
            ])->get();
            $managerRole->permissions()->sync($managerPermissions);
        }

        // Production Manager - Project focus with approvals
        $prodManagerRole = Role::where('title', 'Production Manager')->first();
        if ($prodManagerRole) {
            $prodManagerPermissions = Permission::whereIn('title', [
                'dashboard_access',
                'projects_view',
                'projects_create',
                'projects_edit',
                'projects_approve',
                'talents_view',
                'talents_approve',
                'talents_reject',
                'payments_view',
                'reports_view',
                'notifications_view',
                'notifications_send'
            ])->get();
            $prodManagerRole->permissions()->sync($prodManagerPermissions);
        }

        // Editor - Content focused
        $editorRole = Role::where('title', 'Editor')->first();
        if ($editorRole) {
            $editorPermissions = Permission::whereIn('title', [
                'dashboard_access',
                'projects_view',
                'projects_edit',
                'content_view',
                'content_create',
                'content_edit',
                'content_delete',
                'talents_view',
                'notifications_view'
            ])->get();
            $editorRole->permissions()->sync($editorPermissions);
        }

        // Content Creator - Limited content permissions
        $creatorRole = Role::where('title', 'Content Creator')->first();
        if ($creatorRole) {
            $creatorPermissions = Permission::whereIn('title', [
                'dashboard_access',
                'projects_view',
                'content_view',
                'content_create',
                'content_edit',
                'talents_view',
                'notifications_view'
            ])->get();
            $creatorRole->permissions()->sync($creatorPermissions);
        }

        // Finance Manager - Payment focused
        $financeRole = Role::where('title', 'Finance Manager')->first();
        if ($financeRole) {
            $financePermissions = Permission::whereIn('title', [
                'dashboard_access',
                'payments_view',
                'payments_create',
                'payments_edit',
                'payments_approve',
                'payments_process',
                'reports_view',
                'reports_create',
                'notifications_view'
            ])->get();
            $financeRole->permissions()->sync($financePermissions);
        }

        // Regular Admin - Basic permissions
        $adminRole = Role::where('title', 'Admin')->first();
        if ($adminRole) {
            $adminPermissions = Permission::whereIn('title', [
                'dashboard_access',
                'projects_view',
                'talents_view',
                'payments_view',
                'notifications_view'
            ])->get();
            $adminRole->permissions()->sync($adminPermissions);
        }
    }
}
