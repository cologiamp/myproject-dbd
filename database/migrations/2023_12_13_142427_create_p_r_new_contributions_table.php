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
        Schema::create('p_r_new_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_recommendation_id')->constrained();
            $table->string('tax_year');
            $table->integer('estimated_relevant_earnings')->nullable();
            $table->integer('estimated_adjusted_income')->nullable();
            $table->integer('type')->nullable();
            $table->integer('paid_by')->nullable();
            $table->integer('amount_gross')->nullable();
            $table->integer('frequency')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_r_new_contributions');
    }
};
