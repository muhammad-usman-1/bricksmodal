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
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->string('height_range')->nullable();
            $table->string('weight_range')->nullable();
            $table->string('skin_color')->nullable();
            $table->string('eye_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->dropColumn(['height_range', 'weight_range', 'skin_color', 'eye_color']);
        });
    }
};
