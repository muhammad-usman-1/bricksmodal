<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCastingApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('casting_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('casting_requirement_id')->nullable();
            $table->foreign('casting_requirement_id', 'casting_requirement_fk_10715075')->references('id')->on('casting_requirements');
            $table->unsignedBigInteger('talent_profile_id')->nullable();
            $table->foreign('talent_profile_id', 'talent_profile_fk_10715076')->references('id')->on('talent_profiles');
        });
    }
}
