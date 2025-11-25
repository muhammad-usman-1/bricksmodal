<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        // Admin role (id=1) - basic admin permissions
        $adminPermissions = [1, 2, 3, 4, 5, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 31, 32, 33, 34, 35, 36];
        Role::findOrFail(1)->permissions()->sync($adminPermissions);

        // Superadmin role (id=2) - all permissions including role and permission management
        $superadminPermissions = range(1, 36);
        Role::findOrFail(2)->permissions()->sync($superadminPermissions);

        // Creator role (id=3) - content creation focused permissions
        $creatorPermissions = [14, 15, 16, 18, 20, 21, 22, 24, 31, 32, 33, 34, 36];
        Role::findOrFail(3)->permissions()->sync($creatorPermissions);
    }
}
