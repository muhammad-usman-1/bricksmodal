<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToBankDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('bank_details', function (Blueprint $table) {
            $table->unsignedBigInteger('talent_profile_id')->nullable();
            $table->foreign('talent_profile_id', 'talent_profile_fk_10715087')->references('id')->on('talent_profiles');
        });
    }
}
