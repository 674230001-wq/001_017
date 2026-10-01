<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('porsche_models', function (Blueprint $table) {
            $table->id();
            $table->string('series'); // e.g. 911, 718, Taycan, Panamera, Cayenne, Macan
            $table->string('model_name'); // e.g. 911 GT3 RS (992), Taycan Turbo S
            $table->string('engine_type')->nullable(); // e.g. 4.0L Boxer-6, Dual Motor EV
            $table->string('year_range')->nullable(); // e.g. 2021-2026
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porsche_models');
    }
};
