<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

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
            ]
        );

        // Assign superadmin role
        $superAdminRole = Role::where('title', 'superadmin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->sync([$superAdminRole->id]);
        }

        // Create Regular Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Regular Admin',
                'password' => Hash::make('12345678'),
                'type' => User::TYPE_ADMIN,
            ]
        );

        // Assign admin role
        $adminRole = Role::where('title', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
        }

        // Create Creative
        $creative = User::updateOrCreate(
            ['email' => 'creative@example.com'],
            [
                'name' => 'Content Creative',
                'password' => Hash::make('12345678'),
                'type' => User::TYPE_ADMIN,
            ]
        );

        // Assign creative role
        $creativeRole = Role::where('title', 'creative')->first();
        if ($creativeRole) {
            $creative->roles()->sync([$creativeRole->id]);
        }
    }
}
