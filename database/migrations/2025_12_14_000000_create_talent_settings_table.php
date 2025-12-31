<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('talent_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talent_profile_id')->unique();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('shows_updates')->default(true);
            $table->boolean('show_reminders')->default(true);
            $table->boolean('payment_alerts')->default(false);
            $table->boolean('system_updates')->default(false);
            $table->string('language')->default('English');
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('MM/DD/YYYY');
            $table->string('time_format')->default('12-hour');
            $table->string('appearance')->default('light');
            $table->timestamps();

            $table->foreign('talent_profile_id')
                  ->references('id')
                  ->on('talent_profiles')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_settings');
    }
};

