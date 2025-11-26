<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['title' => 'admin'],
            ['title' => 'superadmin'],
            ['title' => 'creator'],
        ];

        // Idempotent creation: don't rely on explicit primary keys.
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'title' => $role['title'],
            ]);
        }
    }
}
