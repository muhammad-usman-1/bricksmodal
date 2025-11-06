<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\AdminPermission;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'type' => User::TYPE_ADMIN,
                'is_super_admin' => true,
            ]
        );

        $adminRole = Role::firstOrCreate(
            ['id' => 1],
            ['title' => 'Admin']
        );

        $superAdmin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Create Regular Admin with all permissions
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'type' => User::TYPE_ADMIN,
                'is_super_admin' => false,
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Create admin permissions for regular admin
        AdminPermission::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'project_management' => true,
                'talent_management' => true,
                'payment_management' => true,
                'can_make_payments' => false, // Regular admin cannot make payments
            ]
        );
    }
}
