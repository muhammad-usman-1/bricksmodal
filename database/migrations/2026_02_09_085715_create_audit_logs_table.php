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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // 'login_success', 'login_failed', 'logout', 'onboarding_step', 'onboarding_completed', 'signup'
            $table->string('user_type'); // 'admin', 'creative', 'talent'
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // For onboarding events
            $table->string('onboarding_step')->nullable(); // 'step-1', 'step-2', etc.
            $table->integer('onboarding_steps_completed')->nullable();
            $table->boolean('onboarding_completed')->default(false);
            $table->text('onboarding_action')->nullable(); // Description of action performed
            
            // For login events
            $table->boolean('login_successful')->nullable();
            $table->string('login_failure_reason')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable(); // Store additional data
            
            $table->timestamp('created_at');
            
            // Indexes for better query performance
            $table->index(['event_type', 'created_at']);
            $table->index(['user_type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('onboarding_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
