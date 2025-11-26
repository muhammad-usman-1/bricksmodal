<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'user_create',
            ],
            [
                'id'    => 3,
                'title' => 'user_edit',
            ],
            [
                'id'    => 4,
                'title' => 'user_delete',
            ],
            [
                'id'    => 5,
                'title' => 'user_view',
            ],

            // Role Management (Superadmin only)
            [
                'id'    => 6,
                'title' => 'role_management_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_delete',
            ],
            [
                'id'    => 10,
                'title' => 'role_view',
            ],

            // Permission Management (Superadmin only)
            [
                'id'    => 11,
                'title' => 'permission_management_access',
            ],
            [
                'id'    => 12,
                'title' => 'permission_assign',
            ],
            [
                'id'    => 13,
                'title' => 'permission_view',
            ],

            // Project Management
            [
                'id'    => 14,
                'title' => 'project_management_access',
            ],
            [
                'id'    => 15,
                'title' => 'casting_requirement_create',
            ],
            [
                'id'    => 16,
                'title' => 'casting_requirement_edit',
            ],
            [
                'id'    => 17,
                'title' => 'casting_requirement_delete',
            ],
            [
                'id'    => 18,
                'title' => 'casting_requirement_view',
            ],
            [
                'id'    => 19,
                'title' => 'casting_application_manage',
            ],

            // Talent Management
            [
                'id'    => 20,
                'title' => 'talent_management_access',
            ],
            [
                'id'    => 21,
                'title' => 'talent_profile_create',
            ],
            [
                'id'    => 22,
                'title' => 'talent_profile_edit',
            ],
            [
                'id'    => 23,
                'title' => 'talent_profile_delete',
            ],
            [
                'id'    => 24,
                'title' => 'talent_profile_view',
            ],
            [
                'id'    => 25,
                'title' => 'talent_profile_approve',
            ],

            // Payment Management
            [
                'id'    => 26,
                'title' => 'payment_management_access',
            ],
            [
                'id'    => 27,
                'title' => 'payment_request_manage',
            ],
            [
                'id'    => 28,
                'title' => 'payment_approve',
            ],
            [
                'id'    => 29,
                'title' => 'payment_release',
            ],
            [
                'id'    => 30,
                'title' => 'bank_detail_manage',
            ],

            // Content Management
            [
                'id'    => 31,
                'title' => 'content_management_access',
            ],
            [
                'id'    => 32,
                'title' => 'language_manage',
            ],
            [
                'id'    => 33,
                'title' => 'outfit_manage',
            ],
            [
                'id'    => 34,
                'title' => 'email_template_manage',
            ],

            // System Settings
            [
                'id'    => 35,
                'title' => 'system_settings_access',
            ],
            [
                'id'    => 36,
                'title' => 'profile_manage',
            ],
        ];

        // Use idempotent creation so seeding is safe on existing DBs
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'title' => $perm['title'],
            ]);
        }
    }
}
