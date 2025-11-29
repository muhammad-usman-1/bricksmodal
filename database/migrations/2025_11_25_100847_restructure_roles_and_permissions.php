<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete all existing roles
        DB::table('roles')->delete();

        // Delete all existing permissions
        DB::table('permissions')->delete();

        // Create new roles
        $roles = [
            ['id' => 1, 'title' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'superadmin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'creative', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);

        // Create new permissions
        $permissions = [
            // User Management
            ['id' => 1, 'title' => 'user_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'user_create', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'user_edit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'user_delete', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'title' => 'user_view', 'created_at' => now(), 'updated_at' => now()],

            // Role Management (Superadmin only)
            ['id' => 6, 'title' => 'role_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'title' => 'role_create', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'title' => 'role_edit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'title' => 'role_delete', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'title' => 'role_view', 'created_at' => now(), 'updated_at' => now()],

            // Permission Management (Superadmin only)
            ['id' => 11, 'title' => 'permission_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'title' => 'permission_assign', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'title' => 'permission_view', 'created_at' => now(), 'updated_at' => now()],

            // Project Management
            ['id' => 14, 'title' => 'project_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'title' => 'casting_requirement_create', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'title' => 'casting_requirement_edit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'title' => 'casting_requirement_delete', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'title' => 'casting_requirement_view', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'title' => 'casting_application_manage', 'created_at' => now(), 'updated_at' => now()],

            // Talent Management
            ['id' => 20, 'title' => 'talent_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'title' => 'talent_profile_create', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'title' => 'talent_profile_edit', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'title' => 'talent_profile_delete', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'title' => 'talent_profile_view', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'title' => 'talent_profile_approve', 'created_at' => now(), 'updated_at' => now()],

            // Payment Management
            ['id' => 26, 'title' => 'payment_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'title' => 'payment_request_manage', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'title' => 'payment_approve', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'title' => 'payment_release', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'title' => 'bank_detail_manage', 'created_at' => now(), 'updated_at' => now()],

            // Content Management
            ['id' => 31, 'title' => 'content_management_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'title' => 'language_manage', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'title' => 'outfit_manage', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'title' => 'email_template_manage', 'created_at' => now(), 'updated_at' => now()],

            // System Settings
            ['id' => 35, 'title' => 'system_settings_access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'title' => 'profile_manage', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'title' => 'impersonate_user', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'title' => 'casting_application_access', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('permissions')->insert($permissions);

        // Assign permissions to roles
        // Admin role (id=1) - basic admin permissions
        $adminPermissions = [1, 2, 3, 4, 5, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 31, 32, 33, 34, 35, 36, 38];
        DB::table('permission_role')->insert(array_map(function($permId) {
            return ['role_id' => 1, 'permission_id' => $permId];
        }, $adminPermissions));

        // Superadmin role (id=2) - all permissions including role and permission management
        $superadminPermissions = range(1, 38);
        DB::table('permission_role')->insert(array_map(function($permId) {
            return ['role_id' => 2, 'permission_id' => $permId];
        }, $superadminPermissions));

        // Creative role (id=3) - content creation focused permissions
        $creativePermissions = [14, 15, 16, 18, 20, 21, 22, 24, 31, 32, 33, 34, 36];
        DB::table('permission_role')->insert(array_map(function($permId) {
            return ['role_id' => 3, 'permission_id' => $permId];
        }, $creativePermissions));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is destructive and cannot be easily reversed
        // In production, you would need a backup strategy
    }
};
