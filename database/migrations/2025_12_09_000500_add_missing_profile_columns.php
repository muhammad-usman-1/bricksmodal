<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('talent_profiles', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('talent_profiles', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('talent_profiles', 'nationality')) {
                $table->string('nationality')->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('talent_profiles', 'country_code')) {
                $table->string('country_code')->nullable()->after('whatsapp_number');
            }
            if (! Schema::hasColumn('talent_profiles', 'mobile_number')) {
                $table->string('mobile_number')->nullable()->after('country_code');
            }
            if (! Schema::hasColumn('talent_profiles', 'hijab_preference')) {
                $table->string('hijab_preference')->nullable()->after('skin_tone');
            }
            if (! Schema::hasColumn('talent_profiles', 'has_visible_tattoos')) {
                $table->boolean('has_visible_tattoos')->nullable()->after('hijab_preference');
            }
            if (! Schema::hasColumn('talent_profiles', 'has_piercings')) {
                $table->boolean('has_piercings')->nullable()->after('has_visible_tattoos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $drops = [
                'first_name',
                'last_name',
                'nationality',
                'country_code',
                'mobile_number',
                'hijab_preference',
                'has_visible_tattoos',
                'has_piercings',
            ];
            foreach ($drops as $column) {
                if (Schema::hasColumn('talent_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
