<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('password'),
                'phone_country_code' => '',
                'phone_number' => '',
                'otp' => '',
                'type' => User::TYPE_ADMIN,
            ]
        );
    }
}
