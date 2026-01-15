<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->string('instagram_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->dropColumn('instagram_url');
            $table->string('description')->nullable();
        });
    }
};
