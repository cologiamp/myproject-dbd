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
        Schema::create('strategy_recommendation_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_report_recommendation_id')->constrained();
            $table->integer('is_primary')->nullable();
            $table->integer('type')->nullable();
            $table->integer('objective')->nullable();
            $table->string('objective_custom')->nullable();
            $table->integer('what_for')->nullable();
            $table->string('what_for_custom')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_recommendation_objectives');
    }
};
