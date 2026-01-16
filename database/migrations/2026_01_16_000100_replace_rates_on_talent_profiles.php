<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add unified rate column
        Schema::table('talent_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('talent_profiles', 'rate')) {
                $table->float('rate', 15, 2)->nullable()->after('daily_rate');
            }
        });

        // Backfill rate from existing hourly/weekly if present
        if (Schema::hasColumn('talent_profiles', 'rate')) {
            $hasHourly = Schema::hasColumn('talent_profiles', 'hourly_rate');
            $hasWeekly = Schema::hasColumn('talent_profiles', 'weekly_rate');

            if ($hasHourly || $hasWeekly) {
                $parts = [];
                if ($hasHourly) {
                    $parts[] = 'hourly_rate';
                }
                if ($hasWeekly) {
                    $parts[] = 'weekly_rate';
                }

                $expression = implode(' + ', $parts);
                if ($expression !== '') {
                    DB::statement("UPDATE talent_profiles SET rate = COALESCE(rate, {$expression})");
                }
            }
        }

        // Drop legacy columns if they exist
        if (Schema::hasColumn('talent_profiles', 'hourly_rate')) {
            Schema::table('talent_profiles', function (Blueprint $table) {
                $table->dropColumn('hourly_rate');
            });
        }
        if (Schema::hasColumn('talent_profiles', 'weekly_rate')) {
            Schema::table('talent_profiles', function (Blueprint $table) {
                $table->dropColumn('weekly_rate');
            });
        }
    }

    public function down(): void
    {
        // Recreate legacy columns
        Schema::table('talent_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('talent_profiles', 'hourly_rate')) {
                $table->float('hourly_rate', 15, 2)->nullable()->after('daily_rate');
            }
            if (! Schema::hasColumn('talent_profiles', 'weekly_rate')) {
                $table->float('weekly_rate', 15, 2)->nullable()->after('hourly_rate');
            }
        });

        // Backfill hourly_rate from rate if available
        if (Schema::hasColumn('talent_profiles', 'rate')) {
            DB::statement('UPDATE talent_profiles SET hourly_rate = COALESCE(hourly_rate, rate)');
        }

        // Drop unified rate column
        if (Schema::hasColumn('talent_profiles', 'rate')) {
            Schema::table('talent_profiles', function (Blueprint $table) {
                $table->dropColumn('rate');
            });
        }
    }
};
