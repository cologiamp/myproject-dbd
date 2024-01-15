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
        Schema::create('health', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->boolean('is_in_good_health')->nullable();
            $table->boolean('has_life_expectancy_concerns')->nullable();
            $table->text('health_details')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('life_expectancy_details')->nullable();
            $table->integer('smoker')->nullable();
            $table->boolean('smoked_in_last_12_months')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health');
    }
};
