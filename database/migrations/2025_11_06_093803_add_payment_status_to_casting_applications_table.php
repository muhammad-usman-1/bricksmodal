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
            if (!Schema::hasColumn('casting_applications', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('casting_applications', 'payment_requested_by')) {
                $table->foreignId('payment_requested_by')->nullable()->constrained('users')->after('payment_processed');
            }
            if (!Schema::hasColumn('casting_applications', 'payment_requested_at')) {
                $table->timestamp('payment_requested_at')->nullable()->after('payment_requested_by');
            }
            if (!Schema::hasColumn('casting_applications', 'payment_approved_by')) {
                $table->foreignId('payment_approved_by')->nullable()->constrained('users')->after('payment_requested_at');
            }
            if (!Schema::hasColumn('casting_applications', 'payment_approved_at')) {
                $table->timestamp('payment_approved_at')->nullable()->after('payment_approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casting_applications', function (Blueprint $table) {
            if (Schema::hasColumn('casting_applications', 'payment_requested_by')) {
                $table->dropForeign(['payment_requested_by']);
            }
            if (Schema::hasColumn('casting_applications', 'payment_approved_by')) {
                $table->dropForeign(['payment_approved_by']);
            }

            $columnsToCheck = ['payment_status', 'payment_requested_by', 'payment_requested_at', 'payment_approved_by', 'payment_approved_at'];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('casting_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
