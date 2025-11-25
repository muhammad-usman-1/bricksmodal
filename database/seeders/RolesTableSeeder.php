<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'admin',
            ],
            [
                'id'    => 2,
                'title' => 'superadmin',
            ],
            [
                'id'    => 3,
                'title' => 'creator',
            ],
        ];

        Role::insert($roles);
    }
}
