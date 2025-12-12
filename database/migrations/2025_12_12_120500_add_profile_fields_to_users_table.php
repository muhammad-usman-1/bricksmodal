<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('location')->nullable()->after('phone_number');
            $table->string('website')->nullable()->after('location');
            $table->text('bio')->nullable()->after('website');
            $table->string('role_title')->nullable()->after('bio');
            $table->string('member_since')->nullable()->after('role_title');
            $table->string('profile_photo_path')->nullable()->after('member_since');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'location', 'website', 'bio', 'role_title', 'member_since', 'profile_photo_path']);
        });
    }
};
