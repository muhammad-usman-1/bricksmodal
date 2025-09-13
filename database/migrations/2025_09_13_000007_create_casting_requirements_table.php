<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastingRequirementsTable extends Migration
{
    public function up()
    {
        Schema::create('casting_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_name');
            $table->string('client_name')->nullable();
            $table->string('location')->nullable();
            $table->datetime('shoot_date_time')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('age_range')->nullable();
            $table->string('gender')->nullable();
            $table->longText('outfit')->nullable();
            $table->integer('count');
            $table->longText('notes')->nullable();
            $table->float('rate_per_model', 15, 2);
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
