<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('talent_profiles', 'terms_accepted_at')) {
                $table->timestamp('terms_accepted_at')->nullable()->after('onboarding_completed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('talent_profiles', 'terms_accepted_at')) {
                $table->dropColumn('terms_accepted_at');
            }
        });
    }
};

