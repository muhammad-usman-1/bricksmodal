<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->string('rate_decision')->default('talent_decide')->after('rate');
        });

        // Backfill existing rows: any model with a rate gets marked as admin_decide
        DB::table('casting_requirement_models')
            ->whereNotNull('rate')
            ->where('rate', '>', 0)
            ->update(['rate_decision' => 'admin_decide']);
    }

    public function down(): void
    {
        Schema::table('casting_requirement_models', function (Blueprint $table) {
            $table->dropColumn('rate_decision');
        });
    }
};

