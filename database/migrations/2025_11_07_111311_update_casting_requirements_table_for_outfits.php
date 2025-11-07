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
            // First, update any existing text data to empty array JSON
            \DB::statement("UPDATE casting_requirements SET outfit = '[]' WHERE outfit IS NOT NULL AND outfit != ''");
            \DB::statement("UPDATE casting_requirements SET outfit = '[]' WHERE outfit IS NULL");
        });

        Schema::table('casting_requirements', function (Blueprint $table) {
            // Now change the column type to json
            $table->json('outfit')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->longText('outfit')->nullable()->change();
        });
    }
};
