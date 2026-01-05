<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->string('mux_video_asset_id')->nullable()->after('full_body_back_path');
        });
    }

    public function down(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->dropColumn('mux_video_asset_id');
        });
    }
};

