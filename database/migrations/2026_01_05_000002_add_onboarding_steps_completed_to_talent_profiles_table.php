<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->unsignedTinyInteger('onboarding_steps_completed')->default(0)->after('onboarding_step');
        });

        // Normalize existing data: map old 'profile' to 'step-1' and mark completed profiles as 4
        DB::table('talent_profiles')
            ->whereNull('onboarding_step')
            ->update(['onboarding_step' => 'step-1', 'onboarding_steps_completed' => 0]);

        DB::table('talent_profiles')
            ->where('onboarding_step', 'profile')
            ->update(['onboarding_step' => 'step-1']);

        DB::table('talent_profiles')
            ->whereNotNull('onboarding_completed_at')
            ->update(['onboarding_step' => 'step-4', 'onboarding_steps_completed' => 4]);
    }

    public function down(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->dropColumn('onboarding_steps_completed');
        });
    }
};

