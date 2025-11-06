<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCastingRequirementsTable extends Migration
{
    public function up()
    {
        Schema::table('casting_requirements', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_10715069')->references('id')->on('users');
        });
    }
}
