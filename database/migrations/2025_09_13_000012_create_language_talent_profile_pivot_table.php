<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageTalentProfilePivotTable extends Migration
{
    public function up()
    {
        Schema::create('language_talent_profile', function (Blueprint $table) {
            $table->unsignedBigInteger('talent_profile_id');
            $table->foreign('talent_profile_id', 'talent_profile_id_fk_10715008')->references('id')->on('talent_profiles')->onDelete('cascade');
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id', 'language_id_fk_10715008')->references('id')->on('languages')->onDelete('cascade');
        });
    }
}
