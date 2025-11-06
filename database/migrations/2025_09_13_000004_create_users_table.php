<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('phone_country_code')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('otp')->nullable();
            $table->datetime('otp_expires_at')->nullable();
            $table->integer('otp_attempts')->nullable();
            $table->boolean('otp_consumed')->default(0)->nullable();
            $table->string('otp_channel')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
