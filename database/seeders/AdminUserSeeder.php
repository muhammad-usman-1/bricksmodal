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
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('12345678'),
                'phone_country_code' => '+971',
                'phone_number' => '1231234567',
                'location' => 'Dubai, UAE',
                'website' => 'bricks.studio',
                'bio' => 'Experienced studio manager with a passion for creating exceptional visual content. Managing talent and shoots at Bricks Studio.',
                'role_title' => 'Studio Manager',
                'member_since' => 'January 2024',
                'profile_photo_path' => null,
                'type' => User::TYPE_ADMIN,
                'is_super_admin' => true,
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
                'first_name' => 'Regular',
                'last_name' => 'Admin',
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
                'first_name' => 'Content',
                'last_name' => 'Creative',
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
