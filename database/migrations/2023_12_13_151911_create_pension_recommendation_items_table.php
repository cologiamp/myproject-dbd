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
        Schema::create('pension_recommendation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_recommendation_id');
            $table->integer('type');
            $table->integer('value');
            $table->integer('is_percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pension_recommendation_items');
    }
};
