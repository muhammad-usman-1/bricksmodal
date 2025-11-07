<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add card_number to talent_profiles for one-time entry
        Schema::table('talent_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('talent_profiles', 'card_number')) {
                $table->string('card_number')->nullable()->after('whatsapp_number');
            }
            if (!Schema::hasColumn('talent_profiles', 'card_holder_name')) {
                $table->string('card_holder_name')->nullable()->after('card_number');
            }
        });

        // Update casting_applications payment workflow
        Schema::table('casting_applications', function (Blueprint $table) {
            // First, make payment_processed nullable if it exists (to avoid issues with existing data)
            if (Schema::hasColumn('casting_applications', 'payment_processed')) {
                DB::statement('ALTER TABLE casting_applications MODIFY payment_processed VARCHAR(255) NULL');
            }
        });

        Schema::table('casting_applications', function (Blueprint $table) {
            // Now drop payment_processed
            if (Schema::hasColumn('casting_applications', 'payment_processed')) {
                $table->dropColumn('payment_processed');
            }

            // Add comprehensive payment status tracking
            if (!Schema::hasColumn('casting_applications', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'requested', 'approved', 'released', 'received', 'rejected'])
                    ->default('pending')
                    ->after('status');
            }            // Track who requested payment (admin)
            if (!Schema::hasColumn('casting_applications', 'payment_requested_by_admin_id')) {
                $table->unsignedBigInteger('payment_requested_by_admin_id')->nullable()->after('payment_status');
            }

            if (!Schema::hasColumn('casting_applications', 'payment_requested_at')) {
                $table->timestamp('payment_requested_at')->nullable()->after('payment_requested_by_admin_id');
            }

            // Track super admin approval
            if (!Schema::hasColumn('casting_applications', 'payment_approved_by_super_admin_id')) {
                $table->unsignedBigInteger('payment_approved_by_super_admin_id')->nullable()->after('payment_requested_at');
            }

            if (!Schema::hasColumn('casting_applications', 'payment_approved_at')) {
                $table->timestamp('payment_approved_at')->nullable()->after('payment_approved_by_super_admin_id');
            }

            // Track payment release
            if (!Schema::hasColumn('casting_applications', 'payment_released_at')) {
                $table->timestamp('payment_released_at')->nullable()->after('payment_approved_at');
            }

            // Track payment receipt confirmation
            if (!Schema::hasColumn('casting_applications', 'payment_received_at')) {
                $table->timestamp('payment_received_at')->nullable()->after('payment_released_at');
            }

            // Add rejection reason
            if (!Schema::hasColumn('casting_applications', 'payment_rejection_reason')) {
                $table->text('payment_rejection_reason')->nullable()->after('payment_received_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $columns = ['card_number', 'card_holder_name'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('talent_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('casting_applications', function (Blueprint $table) {
            $columns = [
                'payment_status',
                'payment_requested_by_admin_id',
                'payment_requested_at',
                'payment_approved_by_super_admin_id',
                'payment_approved_at',
                'payment_released_at',
                'payment_received_at',
                'payment_rejection_reason'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('casting_applications', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Restore payment_processed
            if (!Schema::hasColumn('casting_applications', 'payment_processed')) {
                $table->string('payment_processed')->default('n/a');
            }
        });
    }
};
