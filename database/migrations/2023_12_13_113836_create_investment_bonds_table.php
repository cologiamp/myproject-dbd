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
        Schema::create('investment_bonds', function (Blueprint $table) {
            $table->id();
            $table->integer('investmentbondable_id'); //made polymorphic to allow both InvestmentRecommendations and Client InvestmentRecommendations to be bonded
            $table->string('investmentbondable_type');
            $table->string('provider')->nullable();
            $table->integer('initial_investment')->nullable();
            $table->integer('surrender_value')->nullable();
            $table->integer('withdrawals')->nullable();
            $table->integer('total_gain')->nullable();
            $table->integer('complete_years_held')->nullable();
            $table->integer('top_slice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_bonds');
    }
};
