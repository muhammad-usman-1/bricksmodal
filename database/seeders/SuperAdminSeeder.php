<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(
            ['id' => 3],
            ['title' => 'Super Admin']
        );

        // Create Super Admin specific permissions
        $permissions = [
            'admin_management_access',
            'admin_create',
            'admin_edit',
            'admin_show',
            'admin_delete',
            'super_admin_access'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['title' => $permission]);
        }

        // Get all permissions for Super Admin (they should have everything)
        $allPermissions = Permission::all();
        $superAdminRole->permissions()->sync($allPermissions);

        // Create Super Admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'type' => User::TYPE_ADMIN,
            ]
        );

        $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);

        // Make sure regular admin role has all permissions except super admin specific ones
        $adminRole = Role::find(1);
        if ($adminRole) {
            $adminPermissions = Permission::whereNotIn('title', [
                'admin_management_access',
                'admin_create',
                'admin_edit',
                'admin_show',
                'admin_delete',
                'super_admin_access'
            ])->get();
            $adminRole->permissions()->sync($adminPermissions);
        }
    }
}
