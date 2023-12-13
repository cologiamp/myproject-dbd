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
        Schema::create('investment_recommendation_items', function (Blueprint $table) {
            $table->id();
            $table->integer('investmentrecommendationable_id'); //made polymorphic to allow both InvestmentRecommendations and Client InvestmentRecommendations to be bonded
            $table->string('investmentrecommendationable_type');
            $table->integer('type')->nullable();
            $table->integer('number_of_units')->nullable();
            $table->string('source_plan')->nullable();
            $table->string('stock_type')->nullable();
            $table->integer('description')->nullable();
            $table->integer('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_recommendations');
    }
};
