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
        Schema::table('casting_applications', function (Blueprint $table) {
            $table->timestamp('payment_requested_at')->nullable();
            $table->timestamp('payment_released_at')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();

            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_applications', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['payment_requested_at', 'payment_released_at', 'requested_by', 'processed_by']);
        });
    }
};
