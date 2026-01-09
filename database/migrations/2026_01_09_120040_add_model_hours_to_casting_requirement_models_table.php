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
            $table->unsignedInteger('model_hours')->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->dropColumn('model_hours');
        });
    }
};
