<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('label_talent_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id')->constrained()->cascadeOnDelete();
            $table->foreignId('talent_profile_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['label_id', 'talent_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('label_talent_profile');
    }
};

