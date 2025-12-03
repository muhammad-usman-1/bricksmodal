<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casting_requirement_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casting_requirement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('rate', 10, 2)->nullable();
            $table->string('gender')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('age_range_key')->nullable();
            $table->unsignedTinyInteger('min_age')->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();
            $table->timestamps();
        });

        Schema::create('casting_requirement_model_label', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('casting_requirement_model_id');
            $table->unsignedBigInteger('label_id');

            // Short foreign key names (important!)
            $table->foreign('casting_requirement_model_id', 'crm_model_fk')
                ->references('id')
                ->on('casting_requirement_models')
                ->cascadeOnDelete();

            $table->foreign('label_id', 'crm_label_fk')
                ->references('id')
                ->on('labels')
                ->cascadeOnDelete();

            $table->unique(['casting_requirement_model_id', 'label_id'], 'crm_label_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casting_requirement_model_label');
        Schema::dropIfExists('casting_requirement_models');
    }
};
