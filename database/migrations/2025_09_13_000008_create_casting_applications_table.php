<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastingApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('casting_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('rate', 15, 2);
            $table->float('rate_offered', 15, 2)->nullable();
            $table->longText('talent_notes')->nullable();
            $table->longText('admin_notes')->nullable();
            $table->string('status');
            $table->integer('rating')->nullable();
            $table->longText('reviews')->nullable();
            $table->string('payment_processed');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
