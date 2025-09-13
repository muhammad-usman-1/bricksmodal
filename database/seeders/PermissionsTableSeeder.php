<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
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
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'talent_profile_create',
            ],
            [
                'id'    => 18,
                'title' => 'talent_profile_edit',
            ],
            [
                'id'    => 19,
                'title' => 'talent_profile_show',
            ],
            [
                'id'    => 20,
                'title' => 'talent_profile_delete',
            ],
            [
                'id'    => 21,
                'title' => 'talent_profile_access',
            ],
            [
                'id'    => 22,
                'title' => 'language_create',
            ],
            [
                'id'    => 23,
                'title' => 'language_edit',
            ],
            [
                'id'    => 24,
                'title' => 'language_show',
            ],
            [
                'id'    => 25,
                'title' => 'language_delete',
            ],
            [
                'id'    => 26,
                'title' => 'language_access',
            ],
            [
                'id'    => 27,
                'title' => 'casting_requirement_create',
            ],
            [
                'id'    => 28,
                'title' => 'casting_requirement_edit',
            ],
            [
                'id'    => 29,
                'title' => 'casting_requirement_show',
            ],
            [
                'id'    => 30,
                'title' => 'casting_requirement_delete',
            ],
            [
                'id'    => 31,
                'title' => 'casting_requirement_access',
            ],
            [
                'id'    => 32,
                'title' => 'casting_application_create',
            ],
            [
                'id'    => 33,
                'title' => 'casting_application_edit',
            ],
            [
                'id'    => 34,
                'title' => 'casting_application_show',
            ],
            [
                'id'    => 35,
                'title' => 'casting_application_delete',
            ],
            [
                'id'    => 36,
                'title' => 'casting_application_access',
            ],
            [
                'id'    => 37,
                'title' => 'bank_detail_create',
            ],
            [
                'id'    => 38,
                'title' => 'bank_detail_edit',
            ],
            [
                'id'    => 39,
                'title' => 'bank_detail_show',
            ],
            [
                'id'    => 40,
                'title' => 'bank_detail_delete',
            ],
            [
                'id'    => 41,
                'title' => 'bank_detail_access',
            ],
            [
                'id'    => 42,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
