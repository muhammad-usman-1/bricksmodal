<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('talent_updates')->default(true);
            $table->boolean('shoot_reminders')->default(true);
            $table->boolean('payment_alerts')->default(false);
            $table->boolean('system_updates')->default(false);
            $table->string('language')->default('English');
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('MM/DD/YYYY');
            $table->string('time_format')->default('12-hour');
            $table->string('appearance')->default('light');
            $table->timestamps();
        });

        DB::table('admin_settings')->insert([
            'id' => 1,
            'email_notifications' => true,
            'push_notifications' => true,
            'talent_updates' => true,
            'shoot_reminders' => true,
            'payment_alerts' => false,
            'system_updates' => false,
            'language' => 'English',
            'timezone' => 'UTC',
            'date_format' => 'MM/DD/YYYY',
            'time_format' => '12-hour',
            'appearance' => 'light',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
