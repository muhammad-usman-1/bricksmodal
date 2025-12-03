<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('shoot_date_time');
        });
    }

    public function down(): void
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};



