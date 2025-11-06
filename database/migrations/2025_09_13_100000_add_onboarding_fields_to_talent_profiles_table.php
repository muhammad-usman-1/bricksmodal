<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('hourly_rate');
            $table->string('gender')->nullable()->after('date_of_birth');

            $table->string('id_front_path')->nullable()->after('user_id');
            $table->string('id_back_path')->nullable()->after('id_front_path');
            $table->string('headshot_center_path')->nullable()->after('id_back_path');
            $table->string('headshot_left_path')->nullable()->after('headshot_center_path');
            $table->string('headshot_right_path')->nullable()->after('headshot_left_path');
            $table->string('full_body_front_path')->nullable()->after('headshot_right_path');
            $table->string('full_body_right_path')->nullable()->after('full_body_front_path');
            $table->string('full_body_back_path')->nullable()->after('full_body_right_path');

            $table->string('onboarding_step')->default('profile')->after('full_body_back_path');
            $table->timestamp('onboarding_completed_at')->nullable()->after('onboarding_step');
        });
    }

    public function down(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'id_front_path',
                'id_back_path',
                'headshot_center_path',
                'headshot_left_path',
                'headshot_right_path',
                'full_body_front_path',
                'full_body_right_path',
                'full_body_back_path',
                'onboarding_step',
                'onboarding_completed_at',
            ]);
        });
    }
};
