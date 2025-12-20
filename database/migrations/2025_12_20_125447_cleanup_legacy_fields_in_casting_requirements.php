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
            $table->dropColumn(['hair_color', 'age_range', 'gender', 'outfit', 'rate_per_model']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->string('hair_color')->nullable();
            $table->string('age_range')->nullable();
            $table->string('gender')->nullable();
            $table->longText('outfit')->nullable();
            $table->float('rate_per_model', 15, 2)->default(0);
        });
    }
};
