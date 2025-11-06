<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE talent_profiles MODIFY height DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY weight DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY chest DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY waist DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY hips DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY shoe_size DECIMAL(8,2) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE talent_profiles MODIFY height INT NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY weight INT NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY chest INT NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY waist INT NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY hips INT NULL');
        DB::statement('ALTER TABLE talent_profiles MODIFY shoe_size INT NULL');
    }
};
