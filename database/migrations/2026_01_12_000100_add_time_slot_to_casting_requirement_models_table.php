<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->string('time_slot')->nullable()->after('eye_color');
        });
    }

    public function down(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->dropColumn('time_slot');
        });
    }
};

