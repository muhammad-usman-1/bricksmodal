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
            $table->unsignedBigInteger('male_top_id')->nullable();
            $table->unsignedBigInteger('male_bottom_id')->nullable();
            $table->unsignedBigInteger('male_traditional_id')->nullable();
            $table->unsignedBigInteger('female_top_id')->nullable();
            $table->unsignedBigInteger('female_bottom_id')->nullable();
            $table->unsignedBigInteger('child_top_id')->nullable();
            $table->unsignedBigInteger('child_bottom_id')->nullable();

            $table->foreign('male_top_id')->references('id')->on('outfits')->onDelete('set null');
            $table->foreign('male_bottom_id')->references('id')->on('outfits')->onDelete('set null');
            $table->foreign('male_traditional_id')->references('id')->on('outfits')->onDelete('set null');
            $table->foreign('female_top_id')->references('id')->on('outfits')->onDelete('set null');
            $table->foreign('female_bottom_id')->references('id')->on('outfits')->onDelete('set null');
            $table->foreign('child_top_id')->references('id')->on('outfits')->onDelete('set null');
            $table->foreign('child_bottom_id')->references('id')->on('outfits')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->dropForeign(['male_top_id']);
            $table->dropForeign(['male_bottom_id']);
            $table->dropForeign(['male_traditional_id']);
            $table->dropForeign(['female_top_id']);
            $table->dropForeign(['female_bottom_id']);
            $table->dropForeign(['child_top_id']);
            $table->dropForeign(['child_bottom_id']);

            $table->dropColumn([
                'male_top_id', 'male_bottom_id', 'male_traditional_id',
                'female_top_id', 'female_bottom_id',
                'child_top_id', 'child_bottom_id'
            ]);
        });
    }
};
