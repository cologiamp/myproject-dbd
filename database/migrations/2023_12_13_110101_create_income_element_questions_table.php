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
        Schema::create('income_element_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_profile_id')->constrained();
            $table->boolean('income_element_capital_at_risk')->nullable();
            $table->boolean('bond_investment_risk_accepted')->nullable();
            $table->boolean('less_risky_are_more_expensive')->nullable();
            $table->boolean('less_risky_are_more_expensive_pay_more')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_element_questions');
    }
};
