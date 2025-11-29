<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        $permissionExists = DB::table('permissions')
            ->where('title', 'casting_application_access')
            ->exists();

        if (! $permissionExists) {
            DB::table('permissions')->insert([
                'id'         => 38,
                'title'      => 'casting_application_access',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ([1, 2] as $roleId) {
            $alreadyAssigned = DB::table('permission_role')
                ->where('role_id', $roleId)
                ->where('permission_id', 38)
                ->exists();

            if (! $alreadyAssigned) {
                DB::table('permission_role')->insert([
                    'role_id'       => $roleId,
                    'permission_id' => 38,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permission_role')->where('permission_id', 38)->delete();
        DB::table('permissions')->where('id', 38)->delete();
    }
};

