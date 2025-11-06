<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalentProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('talent_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('legal_name');
            $table->string('display_name')->nullable();
            $table->string('verification_status')->nullable();
            $table->string('verification_notes')->nullable();
            $table->string('bio')->nullable();
            $table->float('daily_rate', 15, 2);
            $table->float('hourly_rate', 15, 2)->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('chest')->nullable();
            $table->integer('waist')->nullable();
            $table->integer('hips')->nullable();
            $table->string('skin_tone')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('eye_color')->nullable();
            $table->integer('shoe_size')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
