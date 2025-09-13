<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTalentProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('talent_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_10715080')->references('id')->on('users');
        });
    }
}
