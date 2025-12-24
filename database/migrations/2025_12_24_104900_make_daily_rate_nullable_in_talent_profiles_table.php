<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDailyRateNullableInTalentProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->float('daily_rate', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->float('daily_rate', 15, 2)->nullable(false)->change();
        });
    }
}
