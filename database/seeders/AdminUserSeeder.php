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
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'type' => User::TYPE_ADMIN,
            ]
        );

        $adminRole = Role::firstOrCreate(
            ['id' => 1],
            ['title' => 'Admin']
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
